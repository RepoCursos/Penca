<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PencaKP - Penca Mundial</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-center text-yellow-400 mb-8">🏆 PencaKP - Mundial</h1>

        <!-- Tabla de posiciones -->
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-center">Tabla de Posiciones</h2>
            <div class="grid grid-cols-2 gap-4 max-w-md mx-auto">
                @foreach ($standings as $user => $points)
                    <div class="text-center p-4 rounded-lg {{ $loop->first ? 'bg-blue-900' : 'bg-green-900' }}">
                        <p class="text-lg font-medium">{{ $user }}</p>
                        <p class="text-3xl font-bold text-yellow-400">{{ $points }}</p>
                        <p class="text-sm text-gray-400">puntos</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="flex justify-center gap-4 mb-8">
            <button onclick="openCreateMatchModal()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition">
                + Nuevo Partido
            </button>
        </div>

        <!-- Lista de partidos -->
        <div class="bg-gray-800 rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">Partidos</h2>

            @if ($matches->isEmpty())
                <p class="text-gray-400 text-center py-8">No hay partidos registrados aún.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="py-3 px-4 text-left">Fecha</th>
                                <th class="py-3 px-4 text-left">Hora</th>
                                <th class="py-3 px-4 text-left">Local</th>
                                <th class="py-3 px-4 text-left">Visitante</th>
                                <th class="py-3 px-4 text-center">Resultado</th>
                                <th class="py-3 px-4 text-center">Estado</th>
                                <th class="py-3 px-4 text-center">Pronóstico Paulo</th>
                                <th class="py-3 px-4 text-center">Pronóstico Karina</th>
                                <th class="py-3 px-4 text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($matches as $match)
                                <tr class="border-b border-gray-700 hover:bg-gray-750">
                                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($match->match_date)->format('d/m/Y') }}</td>
                                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($match->match_time)->format('H:i') }}</td>
                                    <td class="py-3 px-4 font-medium">{{ $match->team1->name ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 font-medium">{{ $match->team2->name ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-center">
                                        @if ($match->is_completed)
                                            <span class="text-green-400 font-bold">{{ $match->score1 }} - {{ $match->score2 }}</span>
                                        @else
                                            <span class="text-yellow-400">Pendiente</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if ($match->is_completed)
                                            <span class="text-green-400">Finalizado</span>
                                        @elseif ($match->isPredictionLocked())
                                            <span class="text-red-400">Bloqueado</span>
                                        @else
                                            <span class="text-blue-400">Abierto</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @php
                                            $predictionPaulo = $match->getResultForUser('Paulo');
                                        @endphp
                                        @if ($predictionPaulo)
                                            <span class="font-mono">{{ $predictionPaulo->score1 }} - {{ $predictionPaulo->score2 }}</span>
                                            @if ($match->is_completed)
                                                <span class="text-xs ml-1 {{ $predictionPaulo->points > 0 ? 'text-green-400' : 'text-red-400' }}">({{ $predictionPaulo->points }}pts)</span>
                                            @endif
                                        @else
                                            <button onclick="openPredictionModal({{ $match->id }}, 'Paulo', {{ $match->team1_id }}, {{ $match->team2_id }})" class="text-blue-400 hover:text-blue-300 text-xs {{ $match->isPredictionLocked() ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $match->isPredictionLocked() ? 'disabled' : '' }}>
                                                Pronosticar
                                            </button>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @php
                                            $predictionKarina = $match->getResultForUser('Karina');
                                        @endphp
                                        @if ($predictionKarina)
                                            <span class="font-mono">{{ $predictionKarina->score1 }} - {{ $predictionKarina->score2 }}</span>
                                            @if ($match->is_completed)
                                                <span class="text-xs ml-1 {{ $predictionKarina->points > 0 ? 'text-green-400' : 'text-red-400' }}">({{ $predictionKarina->points }}pts)</span>
                                            @endif
                                        @else
                                            <button onclick="openPredictionModal({{ $match->id }}, 'Karina', {{ $match->team1_id }}, {{ $match->team2_id }})" class="text-blue-400 hover:text-blue-300 text-xs {{ $match->isPredictionLocked() ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $match->isPredictionLocked() ? 'disabled' : '' }}>
                                                Pronosticar
                                            </button>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if (!$match->is_completed && $match->canLoadResult())
                                            <button onclick="openResultModal({{ $match->id }}, '{{ $match->team1->name }}', '{{ $match->team2->name }}')" class="text-green-400 hover:text-green-300 text-xs">
                                                Cargar Resultado
                                            </button>
                                        @elseif (!$match->is_completed)
                                            <span class="text-xs text-gray-500">Esperando...</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal: Nuevo Partido -->
    <div id="createMatchModal" class="fixed inset-0 bg-black bg-opacity-60 hidden flex items-center justify-center z-50">
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-xl font-semibold mb-4">Nuevo Partido</h3>
            <form id="createMatchForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Equipo Local</label>
                    <select name="team1_id" class="w-full bg-gray-700 rounded px-3 py-2 text-white" required>
                        <option value="">Seleccionar equipo</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}">[{{ $team->group_letter }}] {{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Equipo Visitante</label>
                    <select name="team2_id" class="w-full bg-gray-700 rounded px-3 py-2 text-white" required>
                        <option value="">Seleccionar equipo</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}">[{{ $team->group_letter }}] {{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Fecha del Partido</label>
                    <input type="date" name="match_date" class="w-full bg-gray-700 rounded px-3 py-2 text-white" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Hora del Partido</label>
                    <input type="time" name="match_time" class="w-full bg-gray-700 rounded px-3 py-2 text-white" required>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeCreateMatchModal()" class="px-4 py-2 bg-gray-600 rounded hover:bg-gray-500 transition">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 rounded hover:bg-green-700 transition">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Pronóstico -->
    <div id="predictionModal" class="fixed inset-0 bg-black bg-opacity-60 hidden flex items-center justify-center z-50">
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-sm mx-4">
            <h3 class="text-xl font-semibold mb-4">Pronóstico</h3>
            <form id="predictionForm">
                @csrf
                <input type="hidden" name="match_id" id="predictionMatchId">
                <input type="hidden" name="user_name" id="predictionUserName">
                <div class="mb-4">
                    <p class="text-sm text-gray-400 mb-2" id="predictionTeams"></p>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-1" id="predictionLabel1">Local</label>
                        <input type="number" name="score1" min="0" class="w-full bg-gray-700 rounded px-3 py-2 text-white text-center text-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" id="predictionLabel2">Visitante</label>
                        <input type="number" name="score2" min="0" class="w-full bg-gray-700 rounded px-3 py-2 text-white text-center text-lg" required>
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closePredictionModal()" class="px-4 py-2 bg-gray-600 rounded hover:bg-gray-500 transition">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 rounded hover:bg-blue-700 transition">Guardar Pronóstico</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Resultado -->
    <div id="resultModal" class="fixed inset-0 bg-black bg-opacity-60 hidden flex items-center justify-center z-50">
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-sm mx-4">
            <h3 class="text-xl font-semibold mb-4">Cargar Resultado</h3>
            <form id="resultForm">
                @csrf
                <input type="hidden" name="match_id" id="resultMatchId">
                <div class="mb-4">
                    <p class="text-sm text-gray-400 mb-2" id="resultTeams"></p>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-1" id="resultLabel1">Local</label>
                        <input type="number" name="score1" min="0" class="w-full bg-gray-700 rounded px-3 py-2 text-white text-center text-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" id="resultLabel2">Visitante</label>
                        <input type="number" name="score2" min="0" class="w-full bg-gray-700 rounded px-3 py-2 text-white text-center text-lg" required>
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeResultModal()" class="px-4 py-2 bg-gray-600 rounded hover:bg-gray-500 transition">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 rounded hover:bg-green-700 transition">Guardar Resultado</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // --- Create Match ---
        function openCreateMatchModal() {
            document.getElementById('createMatchModal').classList.remove('hidden');
        }

        function closeCreateMatchModal() {
            document.getElementById('createMatchModal').classList.add('hidden');
        }

        document.getElementById('createMatchForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            try {
                const res = await fetch('{{ route('penca.storeMatch') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: formData
                });

                if (!res.ok) {
                    const data = await res.json();
                    alert(Object.values(data.errors || data).flat().join('\n'));
                    return;
                }

                location.reload();
            } catch (err) {
                alert('Error al crear el partido');
            }
        });

        // --- Prediction ---
        let predictionTeam1Name = '';
        let predictionTeam2Name = '';

        function openPredictionModal(matchId, userName, team1Id, team2Id) {
            fetchTeamNames(matchId, team1Id, team2Id).then(({ team1, team2 }) => {
                predictionTeam1Name = team1;
                predictionTeam2Name = team2;
                document.getElementById('predictionMatchId').value = matchId;
                document.getElementById('predictionUserName').value = userName;
                document.getElementById('predictionTeams').textContent = `${team1} vs ${team2} - ${userName}`;
                document.getElementById('predictionLabel1').textContent = team1;
                document.getElementById('predictionLabel2').textContent = team2;
                document.getElementById('predictionForm').querySelectorAll('input[type="number"]').forEach(i => i.value = '');
                document.getElementById('predictionModal').classList.remove('hidden');
            });
        }

        function closePredictionModal() {
            document.getElementById('predictionModal').classList.add('hidden');
        }

        document.getElementById('predictionForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            try {
                const res = await fetch('{{ route('penca.storePrediction') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: formData
                });

                if (!res.ok) {
                    const data = await res.json();
                    alert(data.error || Object.values(data.errors || data).flat().join('\n'));
                    return;
                }

                location.reload();
            } catch (err) {
                alert('Error al guardar el pronóstico');
            }
        });

        // --- Result ---
        function openResultModal(matchId, team1, team2) {
            document.getElementById('resultMatchId').value = matchId;
            document.getElementById('resultTeams').textContent = team1 + ' vs ' + team2;
            document.getElementById('resultLabel1').textContent = team1;
            document.getElementById('resultLabel2').textContent = team2;
            document.getElementById('resultForm').querySelectorAll('input[type="number"]').forEach(i => i.value = '');
            document.getElementById('resultModal').classList.remove('hidden');
        }

        function closeResultModal() {
            document.getElementById('resultModal').classList.add('hidden');
        }

        document.getElementById('resultForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const matchId = document.getElementById('resultMatchId').value;
            const formData = new FormData(this);

            try {
                const res = await fetch(`/matches/${matchId}/result`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: formData
                });

                if (!res.ok) {
                    const data = await res.json();
                    alert(data.error || Object.values(data.errors || data).flat().join('\n'));
                    return;
                }

                location.reload();
            } catch (err) {
                alert('Error al guardar el resultado');
            }
        });

        // --- Helpers ---
        async function fetchTeamNames(matchId, team1Id, team2Id) {
            const teams = @json($teams);
            const t1 = teams.find(t => t.id == team1Id);
            const t2 = teams.find(t => t.id == team2Id);
            return { team1: t1 ? t1.name : 'Local', team2: t2 ? t2.name : 'Visitante' };
        }

        // Cerrar modales con Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeCreateMatchModal();
                closePredictionModal();
                closeResultModal();
            }
        });
    </script>
</body>
</html>
