<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;
use App\Models\Genre;
use App\Models\GameImage;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $games = [
            [
                'title' => 'Cyberpunk 2077',
                'description' => 'Cyberpunk 2077 — это приключенческая ролевая игра, действие которой происходит в мегаполисе Найт-Сити, где власть, роскошь и модификации тела ценятся выше всего. Вы играете за V, наёмника в поисках устройства, позволяющего обрести бессмертие.',
                'price' => 2999.00,
                'discount_price' => 1499.00,
                'system_requirements' => 'ОС: Windows 10 64-bit\nПроцессор: Intel Core i5-3570K или AMD FX-8310\nОперативная память: 8 GB ОЗУ\nВидеокарта: NVIDIA GeForce GTX 780 или AMD Radeon RX 470\nDirectX: версии 12\nМесто на диске: 70 GB',
                'recommended_requirements' => 'ОС: Windows 10 64-bit\nПроцессор: Intel Core i7-4790 или AMD Ryzen 3 3200G\nОперативная память: 12 GB ОЗУ\nВидеокарта: NVIDIA GeForce GTX 1060 6GB или AMD Radeon R9 Fury\nDirectX: версии 12\nМесто на диске: 70 GB',
                'is_featured' => true,
                'is_new' => false,
                'is_on_sale' => true,
                'genres' => ['RPG', 'Экшен']
            ],
            [
                'title' => 'The Witcher 3: Wild Hunt',
                'description' => 'Вы — Геральт из Ривии, наёмный охотник за чудовищами. Перед вами — континент, раздираемый войной, заселённый чудовищами. Вам предстоит принять судьбоносное решение, последствия которого будут поистине эпическими.',
                'price' => 1999.00,
                'discount_price' => null,
                'system_requirements' => 'ОС: Windows 7 64-bit\nПроцессор: Intel CPU Core i5-2500K 3.3GHz / AMD CPU Phenom II X4 940\nОперативная память: 6 GB ОЗУ\nВидеокарта: Nvidia GPU GeForce GTX 660 / AMD GPU Radeon HD 7870\nDirectX: версии 11\nМесто на диске: 35 GB',
                'recommended_requirements' => 'ОС: Windows 10 64-bit\nПроцессор: Intel CPU Core i7 3770 3.4 GHz / AMD CPU AMD FX-8350 4 GHz\nОперативная память: 8 GB ОЗУ\nВидеокарта: Nvidia GPU GeForce GTX 770 / AMD GPU Radeon R9 290\nDirectX: версии 11\nМесто на диске: 35 GB',
                'is_featured' => true,
                'is_new' => false,
                'is_on_sale' => false,
                'genres' => ['RPG', 'Приключения']
            ],
            [
                'title' => 'Grand Theft Auto V',
                'description' => 'Grand Theft Auto V для PC предлагает игрокам возможность исследовать мир Лос-Сантоса и округа Блэйн в разрешении до 4k и выше с частотой 60 кадров в секунду.',
                'price' => 2499.00,
                'discount_price' => 1249.00,
                'system_requirements' => 'ОС: Windows 10 64 Bit, Windows 8.1 64 Bit, Windows 8 64 Bit, Windows 7 64 Bit Service Pack 1\nПроцессор: Intel Core 2 Quad CPU Q6600 @ 2.40GHz (4 CPUs) / AMD Phenom 9850 Quad-Core Processor (4 CPUs) @ 2.5GHz\nОперативная память: 4 GB ОЗУ\nВидеокарта: NVIDIA 9800 GT 1GB / AMD HD 4870 1GB (DX 10, 10.1, 11)\nDirectX: версии 10\nМесто на диске: 72 GB',
                'recommended_requirements' => 'ОС: Windows 10 64 Bit, Windows 8.1 64 Bit, Windows 8 64 Bit, Windows 7 64 Bit Service Pack 1\nПроцессор: Intel Core i5 3470 @ 3.2GHz (4 CPUs) / AMD X8 FX-8350 @ 4GHz (8 CPUs)\nОперативная память: 8 GB ОЗУ\nВидеокарта: NVIDIA GTX 660 2GB / AMD HD 7870 2GB\nDirectX: версии 11\nМесто на диске: 72 GB',
                'is_featured' => false,
                'is_new' => false,
                'is_on_sale' => true,
                'genres' => ['Экшен', 'Приключения']
            ],
            [
                'title' => 'Counter-Strike 2',
                'description' => 'Counter-Strike 2 — это крупнейшее техническое обновление в истории CS. Игра построена на движке Source 2, она включает в себя переработанные карты, улучшенное освещение и многое другое.',
                'price' => 0.00,
                'discount_price' => null,
                'system_requirements' => 'ОС: Windows 10\nПроцессор: 4 hardware CPU threads - Intel® Core™ i5 750 or higher\nОперативная память: 8 GB ОЗУ\nВидеокарта: Video card must be 1 GB or more and should be a DirectX 11-compatible\nDirectX: версии 11\nСеть: Широкополосное подключение к Интернету\nМесто на диске: 85 GB',
                'recommended_requirements' => 'ОС: Windows 10\nПроцессор: 4 hardware CPU threads - Intel® Core™ i5 750 or higher\nОперативная память: 16 GB ОЗУ\nВидеокарта: Video card must be 1 GB or more and should be a DirectX 11-compatible\nDirectX: версии 11\nСеть: Широкополосное подключение к Интернету\nМесто на диске: 85 GB',
                'is_featured' => true,
                'is_new' => true,
                'is_on_sale' => false,
                'genres' => ['Шутер', 'Экшен']
            ],
            [
                'title' => 'Minecraft',
                'description' => 'Minecraft — это игра о размещении блоков и приключениях. Исследуйте случайно сгенерированные миры и стройте удивительные вещи от простейших домов до грандиозных замков.',
                'price' => 1990.00,
                'discount_price' => null,
                'system_requirements' => 'ОС: Windows 7 и выше\nПроцессор: Intel Core i3-3210 3.2 GHz / AMD A8-7600 APU 3.1 GHz или эквивалент\nОперативная память: 4 GB ОЗУ\nВидеокарта: Intel HD Graphics 4000 (Ivy Bridge) или AMD Radeon R5 series (Kaveri line) с OpenGL 4.4\nМесто на диске: 1 GB',
                'recommended_requirements' => 'ОС: Windows 10\nПроцессор: Intel Core i5-4690 3.5GHz / AMD A10-7800 APU 3.5 GHz или эквивалент\nОперативная память: 8 GB ОЗУ\nВидеокарта: GeForce 700 Series или AMD Radeon Rx 200 Series (за исключением интегрированных чипсетов) с OpenGL 4.5\nМесто на диске: 4 GB',
                'is_featured' => false,
                'is_new' => false,
                'is_on_sale' => false,
                'genres' => ['Симулятор', 'Приключения']
            ],
            [
                'title' => 'FIFA 24',
                'description' => 'EA SPORTS FC 24 приветствует вас в новой эре футбола. Почувствуйте близость к игре благодаря трём передовым технологиям, обеспечивающим беспрецедентный реализм.',
                'price' => 4999.00,
                'discount_price' => 3499.00,
                'system_requirements' => 'ОС: Windows 10 64-bit\nПроцессор: Intel Core i5 6600k или AMD Ryzen 5 1600\nОперативная память: 8 GB ОЗУ\nВидеокарта: NVIDIA GeForce GTX 1050 Ti или AMD Radeon RX 570\nDirectX: версии 12\nСеть: Широкополосное подключение к Интернету\nМесто на диске: 100 GB',
                'recommended_requirements' => 'ОС: Windows 10 64-bit\nПроцессор: Intel Core i7 9700K или AMD Ryzen 7 2700X\nОперативная память: 12 GB ОЗУ\nВидеокарта: NVIDIA GeForce RTX 3060 или AMD Radeon RX 6600 XT\nDirectX: версии 12\nСеть: Широкополосное подключение к Интернету\nМесто на диске: 100 GB',
                'is_featured' => false,
                'is_new' => true,
                'is_on_sale' => true,
                'genres' => ['Спорт', 'Симулятор']
            ],
            [
                'title' => 'Forza Horizon 5',
                'description' => 'Ваше приключение Horizon ждёт! Исследуйте яркие и постоянно меняющиеся открытые миры Мексики с неограниченным, веселым вождением в сотнях величайших автомобилей мира.',
                'price' => 3999.00,
                'discount_price' => null,
                'system_requirements' => 'ОС: Windows 10 версии 15063.0 или выше\nПроцессор: Intel i5-4460 или AMD Ryzen 3 1200\nОперативная память: 8 GB ОЗУ\nВидеокарта: NVidia GTX 970 OR AMD RX 470\nDirectX: версии 12\nСеть: Широкополосное подключение к Интернету\nМесто на диске: 110 GB',
                'recommended_requirements' => 'ОС: Windows 10 версии 15063.0 или выше\nПроцессор: Intel i5-8400 или AMD Ryzen 5 1500X\nОперативная память: 16 GB ОЗУ\nВидеокарта: NVidia GTX 1070 OR AMD RX 590\nDirectX: версии 12\nСеть: Широкополосное подключение к Интернету\nМесто на диске: 110 GB',
                'is_featured' => true,
                'is_new' => false,
                'is_on_sale' => false,
                'genres' => ['Гонки', 'Симулятор']
            ],
            [
                'title' => 'Portal 2',
                'description' => 'Portal 2 развивает отмеченную наградами формулу инновационного геймплея, повествования и музыки, которая принесла оригинальной Portal более 70 отраслевых наград.',
                'price' => 999.00,
                'discount_price' => 199.00,
                'system_requirements' => 'ОС: Windows 7 / Vista / XP\nПроцессор: 3.0 GHz P4, Dual Core 2.0 (или выше) или AMD64X2 (или выше)\nОперативная память: 2 GB ОЗУ\nВидеокарта: Video card must be 128 MB or more\nDirectX: версии 9.0c\nМесто на диске: 8 GB',
                'recommended_requirements' => 'ОС: Windows 7\nПроцессор: Intel Core 2 Duo E6600 или AMD Phenom X3 8750\nОперативная память: 4 GB ОЗУ\nВидеокарта: DirectX 9 compatible video card with Shader model 3.0\nDirectX: версии 9.0c\nМесто на диске: 8 GB',
                'is_featured' => false,
                'is_new' => false,
                'is_on_sale' => true,
                'genres' => ['Приключения', 'Стратегия']
            ]
        ];

        foreach ($games as $gameData) {
            // Создаем игру
            $game = Game::create([
                'title' => $gameData['title'],
                'description' => $gameData['description'],
                'price' => $gameData['price'],
                'discount_price' => $gameData['discount_price'],
                'system_requirements' => $gameData['system_requirements'],
                'recommended_requirements' => $gameData['recommended_requirements'],
                'is_featured' => $gameData['is_featured'],
                'is_new' => $gameData['is_new'],
                'is_on_sale' => $gameData['is_on_sale'],
            ]);

            // Привязываем жанры
            foreach ($gameData['genres'] as $genreName) {
                $genre = Genre::where('name', $genreName)->first();
                if ($genre) {
                    $game->genres()->attach($genre->id);
                }
            }

            // Создаем изображение-заглушку для игры
            GameImage::create([
                'game_id' => $game->id,
                'image_path' => '/placeholder.svg?height=300&width=400&text=' . urlencode($game->title),
                'is_primary' => true,
                'order' => 1
            ]);
        }
    }
}
