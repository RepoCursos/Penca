<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Prediction;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PencaController extends Controller
{
    public function index()
    {
        $matches = Game::with(['team1', 'team2'])->orderBy('match_date', 'desc')->orderBy('match_time', 'desc')->get();
        $teams = Team::orderBy('group_letter')->orderBy('name')->get();

        $users = ['Paulo', 'Karina'];
        $standings = [];

        foreach ($users as $user) {
            $standings[$user] = Prediction::where('user_name', $user)->sum('points');
        }

        return view('penca.index', compact('matches', 'users', 'standings', 'teams'));
    }

    public function storeMatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'team1_id' => 'required|exists:teams,id|different:team2_id',
            'team2_id' => 'required|exists:teams,id',
            'match_date' => 'required|date',
            'match_time' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $match = Game::create($request->all());

        return response()->json(['message' => 'Partido creado correctamente', 'match' => $match->load(['team1', 'team2'])]);
    }

    public function storePrediction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|in:Paulo,Karina',
            'match_id' => 'required|exists:matches,id',
            'score1' => 'required|integer|min:0',
            'score2' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $match = Game::findOrFail($request->match_id);

        if ($match->isPredictionLocked()) {
            return response()->json(['error' => 'Ya pasó el tiempo límite para predicciones (30 min antes del partido)'], 422);
        }

        $prediction = Prediction::updateOrCreate(
            ['user_name' => $request->user_name, 'match_id' => $request->match_id],
            ['score1' => $request->score1, 'score2' => $request->score2, 'points' => 0]
        );

        return response()->json(['message' => 'Pronóstico guardado correctamente', 'prediction' => $prediction]);
    }

    public function storeResult(Request $request, $matchId)
    {
        $validator = Validator::make($request->all(), [
            'score1' => 'required|integer|min:0',
            'score2' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $match = Game::findOrFail($matchId);

        if (! $match->canLoadResult()) {
            return response()->json(['error' => 'El resultado se puede cargar 2 horas después del inicio del partido'], 422);
        }

        $match->update([
            'score1' => $request->score1,
            'score2' => $request->score2,
            'is_completed' => true,
        ]);

        $match->refresh();

        $predictions = $match->predictions;

        foreach ($predictions as $prediction) {
            $points = $match->calculatePointsForUser($prediction->user_name);
            $prediction->update(['points' => $points]);
        }

        return response()->json(['message' => 'Resultado guardado y puntos calculados']);
    }

    public function getMatch(Game $match)
    {
        return response()->json([
            'match' => $match,
            'predictions' => $match->predictions,
            'is_locked' => $match->isPredictionLocked(),
        ]);
    }
}
