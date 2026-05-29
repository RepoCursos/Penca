<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $teams = [
            ['name' => 'México', 'group_letter' => 'A'],
            ['name' => 'Sudáfrica', 'group_letter' => 'A'],
            ['name' => 'Corea del Sur', 'group_letter' => 'A'],
            ['name' => 'Chequia', 'group_letter' => 'A'],
            ['name' => 'Canadá', 'group_letter' => 'B'],
            ['name' => 'Bosnia y Herzegovina', 'group_letter' => 'B'],
            ['name' => 'Catar', 'group_letter' => 'B'],
            ['name' => 'Suiza', 'group_letter' => 'B'],
            ['name' => 'Brasil', 'group_letter' => 'C'],
            ['name' => 'Marruecos', 'group_letter' => 'C'],
            ['name' => 'Haití', 'group_letter' => 'C'],
            ['name' => 'Escocia', 'group_letter' => 'C'],
            ['name' => 'Estados Unidos', 'group_letter' => 'D'],
            ['name' => 'Paraguay', 'group_letter' => 'D'],
            ['name' => 'Australia', 'group_letter' => 'D'],
            ['name' => 'Turquía', 'group_letter' => 'D'],
            ['name' => 'Alemania', 'group_letter' => 'E'],
            ['name' => 'Curazao', 'group_letter' => 'E'],
            ['name' => 'Costa de Marfil', 'group_letter' => 'E'],
            ['name' => 'Ecuador', 'group_letter' => 'E'],
            ['name' => 'Países Bajos', 'group_letter' => 'F'],
            ['name' => 'Japón', 'group_letter' => 'F'],
            ['name' => 'Suecia', 'group_letter' => 'F'],
            ['name' => 'Túnez', 'group_letter' => 'F'],
            ['name' => 'Bélgica', 'group_letter' => 'G'],
            ['name' => 'Egipto', 'group_letter' => 'G'],
            ['name' => 'Irán', 'group_letter' => 'G'],
            ['name' => 'Nueva Zelanda', 'group_letter' => 'G'],
            ['name' => 'España', 'group_letter' => 'H'],
            ['name' => 'Cabo Verde', 'group_letter' => 'H'],
            ['name' => 'Arabia Saudita', 'group_letter' => 'H'],
            ['name' => 'Uruguay', 'group_letter' => 'H'],
            ['name' => 'Francia', 'group_letter' => 'I'],
            ['name' => 'Senegal', 'group_letter' => 'I'],
            ['name' => 'Noruega', 'group_letter' => 'I'],
            ['name' => 'Irak', 'group_letter' => 'I'],
            ['name' => 'Argentina', 'group_letter' => 'J'],
            ['name' => 'Argelia', 'group_letter' => 'J'],
            ['name' => 'Austria', 'group_letter' => 'J'],
            ['name' => 'Jordania', 'group_letter' => 'J'],
            ['name' => 'Portugal', 'group_letter' => 'K'],
            ['name' => 'RD Congo', 'group_letter' => 'K'],
            ['name' => 'Uzbekistán', 'group_letter' => 'K'],
            ['name' => 'Colombia', 'group_letter' => 'K'],
            ['name' => 'Inglaterra', 'group_letter' => 'L'],
            ['name' => 'Croacia', 'group_letter' => 'L'],
            ['name' => 'Ghana', 'group_letter' => 'L'],
            ['name' => 'Panamá', 'group_letter' => 'L'],
        ];

        foreach ($teams as $team) {
            Team::create($team);
        }
    }
}
