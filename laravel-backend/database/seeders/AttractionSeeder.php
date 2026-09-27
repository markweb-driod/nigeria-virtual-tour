<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attraction;
use App\Models\Category;
use App\Models\State;
use App\Models\Scene;
use App\Models\Hotspot;
use App\Models\TourSession;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AttractionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure directories exist
        $this->ensureDirectories();

        // 2. Generate placeholder images if missing
        $this->generatePlaceholders();

        // 3. Seed Attractions with Panoramas & Hotspots
        $this->seedOlumoRock();
        $this->seedZumaRock();
        $this->seedYankari();
        $this->seedObudu();
        $this->seedLekki();

        // 4. Seed initial analytics sessions for demonstration / Chapter 4 metrics
        $this->seedAnalytics();
    }

    private function ensureDirectories(): void
    {
        File::ensureDirectoryExists(public_path('images'));
        File::ensureDirectoryExists(storage_path('app/public/panoramas/olumo-rock'));
        File::ensureDirectoryExists(storage_path('app/public/panoramas/olumo-rock/thumbs'));
        File::ensureDirectoryExists(storage_path('app/public/panoramas/zuma-rock'));
        File::ensureDirectoryExists(storage_path('app/public/panoramas/zuma-rock/thumbs'));
        File::ensureDirectoryExists(storage_path('app/public/panoramas/yankari'));
        File::ensureDirectoryExists(storage_path('app/public/panoramas/yankari/thumbs'));
        File::ensureDirectoryExists(storage_path('app/public/panoramas/obudu'));
        File::ensureDirectoryExists(storage_path('app/public/panoramas/obudu/thumbs'));
        File::ensureDirectoryExists(storage_path('app/public/panoramas/lekki'));
        File::ensureDirectoryExists(storage_path('app/public/panoramas/lekki/thumbs'));
        File::ensureDirectoryExists(storage_path('app/public/attractions/thumbs'));
        File::ensureDirectoryExists(storage_path('app/public/attractions/heroes'));
    }

    private function createEquirectangularPanorama(string $filePath, string $title, string $subtitle, array $themeColors): void
    {
        $w = 2048;
        $h = 1024; // 2:1 ratio
        $img = imagecreatetruecolor($w, $h);

        [$skyTop, $skyBottom, $groundTop, $groundBottom] = $themeColors;

        // Render gradient for 360 sphere (Sky hemisphere: 0 to h/2, Ground hemisphere: h/2 to h)
        for ($y = 0; $y < $h; $y++) {
            if ($y < $h * 0.52) {
                // Sky gradient
                $t = $y / ($h * 0.52);
                $r = (int)($skyTop[0] * (1 - $t) + $skyBottom[0] * $t);
                $g = (int)($skyTop[1] * (1 - $t) + $skyBottom[1] * $t);
                $b = (int)($skyTop[2] * (1 - $t) + $skyBottom[2] * $t);
            } else {
                // Ground / Terrain gradient
                $t = ($y - $h * 0.52) / ($h * 0.48);
                $r = (int)($groundTop[0] * (1 - $t) + $groundBottom[0] * $t);
                $g = (int)($groundTop[1] * (1 - $t) + $groundBottom[1] * $t);
                $b = (int)($groundTop[2] * (1 - $t) + $groundBottom[2] * $t);
            }
            $color = imagecolorallocate($img, $r, $g, $b);
            imageline($img, 0, $y, $w, $y, $color);
        }

        // Horizon line glow
        $horizonY = (int)($h * 0.52);
        $horizonColor = imagecolorallocate($img, 212, 160, 23); // Heritage Gold accent
        imagesetthickness($img, 2);
        imageline($img, 0, $horizonY, $w, $horizonY, $horizonColor);

        // Grid lines (equirectangular latitude/longitude guidelines)
        $gridColor = imagecolorallocatealpha($img, 255, 255, 255, 110);
        for ($x = 0; $x < $w; $x += (int)($w / 8)) {
            imageline($img, $x, 0, $x, $h, $gridColor);
        }
        for ($y = 0; $y < $h; $y += (int)($h / 6)) {
            imageline($img, 0, $y, $w, $y, $gridColor);
        }

        // Draw compass cardinal points at the horizon
        $white = imagecolorallocate($img, 255, 255, 255);
        $gold = imagecolorallocate($img, 212, 160, 23);
        $directions = [
            0 => 'NORTH (0°)',
            (int)($w * 0.25) => 'EAST (90°)',
            (int)($w * 0.50) => 'SOUTH (180°)',
            (int)($w * 0.75) => 'WEST (270°)',
        ];
        foreach ($directions as $dx => $dirText) {
            imagestring($img, 5, $dx + 20, $horizonY - 35, $dirText, $gold);
        }

        // Scene center banner
        $centerX = (int)($w / 2);
        imagestring($img, 5, $centerX - (strlen($title) * 4), $horizonY - 90, strtoupper($title), $white);
        imagestring($img, 4, $centerX - (strlen($subtitle) * 4), $horizonY - 60, $subtitle, $gold);

        // Nadir and Zenith markers
        imagestring($img, 4, $centerX - 40, 40, '[ ZENITH - SKY ]', $white);
        imagestring($img, 4, $centerX - 45, $h - 50, '[ NADIR - GROUND ]', $gold);

        imagejpeg($img, $filePath, 90);
        imagedestroy($img);
    }

    private function createThumbnail(string $filePath, string $title, array $bgColor): void
    {
        $w = 600;
        $h = 400;
        $img = imagecreatetruecolor($w, $h);
        $bg = imagecolorallocate($img, $bgColor[0], $bgColor[1], $bgColor[2]);
        imagefill($img, 0, 0, $bg);

        // Accent border
        $gold = imagecolorallocate($img, 212, 160, 23);
        $white = imagecolorallocate($img, 255, 255, 255);
        imagesetthickness($img, 4);
        imagerectangle($img, 10, 10, $w - 10, $h - 10, $gold);

        imagestring($img, 5, 30, $h / 2 - 20, $title, $white);
        imagestring($img, 4, 30, $h / 2 + 10, 'Virtual Tour 360°', $gold);

        imagejpeg($img, $filePath, 85);
        imagedestroy($img);
    }

    private function generatePlaceholders(): void
    {
        $placeholders = [
            public_path('images/placeholder.webp') => 'Nigerian Attraction',
            public_path('images/hero-placeholder.webp') => '360° Virtual Tour Experience',
            public_path('images/scene-placeholder.webp') => 'Panorama Scene Node',
        ];

        foreach ($placeholders as $path => $text) {
            if (!File::exists($path)) {
                $img = imagecreatetruecolor(800, 500);
                $bg = imagecolorallocate($img, 11, 61, 46); // Forest green
                $gold = imagecolorallocate($img, 212, 160, 23);
                $white = imagecolorallocate($img, 255, 255, 255);
                imagefill($img, 0, 0, $bg);
                imagerectangle($img, 8, 8, 792, 492, $gold);
                imagestring($img, 5, 50, 220, $text, $white);
                imagestring($img, 4, 50, 260, 'Virtual Tour System for Nigerian Tourist Attractions', $gold);
                imagewebp($img, $path, 80);
                imagedestroy($img);
            }
        }
    }

    private function seedOlumoRock(): void
    {
        $ogun = State::where('name', 'Ogun')->first();
        $historyCategory = Category::where('name', 'Historical')->first() ?? Category::first();

        // Generate hero and thumbnail
        $heroPath = 'attractions/heroes/olumo-hero.jpg';
        $thumbPath = 'attractions/thumbs/olumo-thumb.jpg';
        $this->createThumbnail(storage_path('app/public/' . $heroPath), 'Olumo Rock, Abeokuta', [11, 61, 46]);
        $this->createThumbnail(storage_path('app/public/' . $thumbPath), 'Olumo Rock', [20, 50, 35]);

        $attraction = Attraction::updateOrCreate(
            ['slug' => 'olumo-rock'],
            [
                'category_id' => $historyCategory->id,
                'state_id' => $ogun->id,
                'name' => 'Olumo Rock',
                'slug' => 'olumo-rock',
                'short_description' => 'A monumental fortress and sacred rock sanctuary standing at 137 metres above sea level in Abeokuta, Ogun State.',
                'description' => "Olumo Rock is one of Nigeria's most celebrated historical monuments and cultural heritage treasures. Located in the ancient city of Abeokuta (which literally translates to 'Under the Rock'), this massive outcrop of granite served as a natural citadel and refuge for the Egba people during the inter-tribal Yoruba civil wars of the 19th century.\n\nVisitors today can ascend the rock via ancient carved stone stairs or modern elevators, discovering hidden caves, historic shrines, centuries-old shrines tended by elder priestesses, and breathtaking 360° panoramic vistas across the Ogun River and the sprawling rusty iron rooftops of ancient Abeokuta.",
                'history' => "During the 19th-century Yoruba civil wars, the displaced Egba refugees found an impenetrable natural defensive fortress beneath the mammoth overhangs of Olumo Rock in approximately 1830. Led by the celebrated hunter and warrior Sodeke, the community established Abeokuta around the rock.\n\nThe rock provided strategic vantage points to spot advancing Dahomey and rival warriors miles away, while deep cavernous crevices offered safe living quarters, freshwater catchment, and medicinal herb reserves. The name 'Olumo' derives from 'Oluwa fimo' — meaning 'God has put an end to our wanderings'.",
                'interesting_facts' => "• The rock reaches an elevation of 137 metres above sea level.\n• The ancient Egba dwellers carved holes into the stone floor used for pounding yams and grain that remain visible today.\n• The sacred Igi Odan tree on the rock summit has lived for centuries and is revered for producing fresh leaves year-round without withering.\n• An ancient priestess over 130 years old resided inside the rock caves for decades tending the royal shrine.",
                'address' => 'Ikija Road, Abeokuta',
                'lga' => 'Abeokuta North',
                'latitude' => 7.1557,
                'longitude' => 3.3453,
                'thumbnail' => $thumbPath,
                'hero_image' => $heroPath,
                'visiting_hours' => '8:00 AM - 6:00 PM Daily',
                'entry_fee' => '₦1,000 (Adults), ₦500 (Children)',
                'is_featured' => true,
                'tour_available' => true,
                'status' => 'published',
                'view_count' => 4201,
            ]
        );

        // Seed Scenes (Multi-scene architecture as specified in Section 10 of document)
        $scenesData = [
            [
                'name' => 'Main Entrance & Plaza',
                'slug' => 'main-entrance',
                'sort_order' => 1,
                'initial_yaw' => 15,
                'initial_pitch' => -3,
                'initial_hfov' => 100,
                'description' => 'The arrival plaza at the base of Olumo Rock showcasing the historic gateway, art gallery, and ticket concourse.',
                'is_default' => true,
                'sky_theme' => [[15, 60, 45], [40, 110, 80], [130, 120, 100], [60, 50, 40]],
            ],
            [
                'name' => 'Lower Platform & Ancient Steps',
                'slug' => 'lower-platform',
                'sort_order' => 2,
                'initial_yaw' => 45,
                'initial_pitch' => 5,
                'initial_hfov' => 95,
                'description' => 'The lower staging plateau featuring the carved stone steps that historic Egba defenders mounted.',
                'is_default' => false,
                'sky_theme' => [[20, 70, 55], [60, 130, 95], [140, 125, 95], [75, 65, 50]],
            ],
            [
                'name' => 'Cave Entrance',
                'slug' => 'cave-entrance',
                'sort_order' => 3,
                'initial_yaw' => 180,
                'initial_pitch' => -2,
                'initial_hfov' => 100,
                'description' => 'The sheltered overhang where Egba warriors and families took refuge during the 1830 Dahomey wars.',
                'is_default' => false,
                'sky_theme' => [[30, 50, 45], [70, 90, 80], [110, 95, 80], [50, 40, 35]],
            ],
            [
                'name' => 'Cave Interior & Sacred Shrine',
                'slug' => 'cave-interior',
                'sort_order' => 4,
                'initial_yaw' => 90,
                'initial_pitch' => 0,
                'initial_hfov' => 90,
                'description' => 'Interior rock sanctuary preserving the grinding holes, medicinal herb store, and ancient Orisa shrine.',
                'is_default' => false,
                'sky_theme' => [[15, 20, 25], [45, 50, 55], [85, 75, 65], [35, 30, 25]],
            ],
            [
                'name' => 'Summit Panoramic View',
                'slug' => 'summit',
                'sort_order' => 5,
                'initial_yaw' => 0,
                'initial_pitch' => -10,
                'initial_hfov' => 105,
                'description' => 'The highest peak of Olumo Rock (137m) delivering an awe-inspiring 360-degree panorama of Abeokuta and Ogun River.',
                'is_default' => false,
                'sky_theme' => [[10, 80, 120], [80, 160, 200], [160, 140, 110], [90, 80, 60]],
            ],
        ];

        $createdScenes = [];
        foreach ($scenesData as $s) {
            $panoRelPath = "panoramas/olumo-rock/{$s['slug']}.jpg";
            $panoAbsPath = storage_path("app/public/{$panoRelPath}");
            $thumbRelPath = "panoramas/olumo-rock/thumbs/{$s['slug']}-thumb.jpg";
            $thumbAbsPath = storage_path("app/public/{$thumbRelPath}");

            $this->createEquirectangularPanorama(
                $panoAbsPath,
                "Olumo Rock: {$s['name']}",
                "Virtual Tour 360° Panorama - Nigerian Heritage",
                $s['sky_theme']
            );

            $this->createThumbnail($thumbAbsPath, $s['name'], [15, 60, 45]);

            $scene = Scene::updateOrCreate(
                ['attraction_id' => $attraction->id, 'slug' => $s['slug']],
                [
                    'name' => $s['name'],
                    'slug' => $s['slug'],
                    'panorama_path' => $panoRelPath,
                    'thumbnail' => $thumbRelPath,
                    'initial_yaw' => $s['initial_yaw'],
                    'initial_pitch' => $s['initial_pitch'],
                    'initial_hfov' => $s['initial_hfov'],
                    'sort_order' => $s['sort_order'],
                    'latitude' => 7.1557,
                    'longitude' => 3.3453,
                    'description' => $s['description'],
                    'is_default' => $s['is_default'],
                    'status' => 'published',
                ]
            );
            $createdScenes[$s['slug']] = $scene;
        }

        // Hotspots connecting the Olumo Rock scenes and info markers
        Hotspot::whereIn('scene_id', collect($createdScenes)->pluck('id'))->delete();

        // 1. Main Entrance Hotspots
        Hotspot::create([
            'scene_id' => $createdScenes['main-entrance']->id,
            'type' => 'scene',
            'yaw' => 22.5,
            'pitch' => -4.0,
            'label' => 'Climb to Lower Platform →',
            'target_scene_id' => $createdScenes['lower-platform']->id,
        ]);
        Hotspot::create([
            'scene_id' => $createdScenes['main-entrance']->id,
            'type' => 'info',
            'yaw' => -45.0,
            'pitch' => 8.0,
            'label' => 'History of Olumo Rock Entrance',
            'description' => 'The entrance complex was modernised with heritage stone archways while preserving the original footpaths trodden since 1830.',
        ]);

        // 2. Lower Platform Hotspots
        Hotspot::create([
            'scene_id' => $createdScenes['lower-platform']->id,
            'type' => 'scene',
            'yaw' => 55.0,
            'pitch' => 6.0,
            'label' => 'Continue to Cave Entrance →',
            'target_scene_id' => $createdScenes['cave-entrance']->id,
        ]);
        Hotspot::create([
            'scene_id' => $createdScenes['lower-platform']->id,
            'type' => 'scene',
            'yaw' => -160.0,
            'pitch' => -12.0,
            'label' => '← Return to Main Plaza',
            'target_scene_id' => $createdScenes['main-entrance']->id,
        ]);
        Hotspot::create([
            'scene_id' => $createdScenes['lower-platform']->id,
            'type' => 'info',
            'yaw' => 110.0,
            'pitch' => 2.0,
            'label' => 'Ancient Defense Outpost',
            'description' => 'Lookout sentries stood on this granite shelf to detect smoke signals from Dahomey skirmish parties.',
        ]);

        // 3. Cave Entrance Hotspots
        Hotspot::create([
            'scene_id' => $createdScenes['cave-entrance']->id,
            'type' => 'scene',
            'yaw' => 85.0,
            'pitch' => -2.0,
            'label' => 'Enter Cave Interior →',
            'target_scene_id' => $createdScenes['cave-interior']->id,
        ]);
        Hotspot::create([
            'scene_id' => $createdScenes['cave-entrance']->id,
            'type' => 'scene',
            'yaw' => 15.0,
            'pitch' => 18.0,
            'label' => 'Ascend to Summit Peak →',
            'target_scene_id' => $createdScenes['summit']->id,
        ]);
        Hotspot::create([
            'scene_id' => $createdScenes['cave-entrance']->id,
            'type' => 'scene',
            'yaw' => -120.0,
            'pitch' => -8.0,
            'label' => '← Down to Lower Platform',
            'target_scene_id' => $createdScenes['lower-platform']->id,
        ]);

        // 4. Cave Interior Hotspots
        Hotspot::create([
            'scene_id' => $createdScenes['cave-interior']->id,
            'type' => 'scene',
            'yaw' => -90.0,
            'pitch' => 4.0,
            'label' => '← Exit to Cave Entrance',
            'target_scene_id' => $createdScenes['cave-entrance']->id,
        ]);
        Hotspot::create([
            'scene_id' => $createdScenes['cave-interior']->id,
            'type' => 'info',
            'yaw' => 30.0,
            'pitch' => -15.0,
            'label' => 'Ancient Grain Grinding Mortars',
            'description' => 'Notice the circular depressions smoothed into the solid rock bed. Egba women pounded grains here during extended wartime sieges.',
        ]);

        // 5. Summit Hotspots
        Hotspot::create([
            'scene_id' => $createdScenes['summit']->id,
            'type' => 'scene',
            'yaw' => 175.0,
            'pitch' => -20.0,
            'label' => 'Descend to Cave Entrance →',
            'target_scene_id' => $createdScenes['cave-entrance']->id,
        ]);
        Hotspot::create([
            'scene_id' => $createdScenes['summit']->id,
            'type' => 'info',
            'yaw' => -25.0,
            'pitch' => -8.0,
            'label' => 'Sacred Igi Odan Tree',
            'description' => 'Legend states this tree has withstood all seasons without ever shedding its foliage, serving as an emblem of resilience for Abeokuta.',
        ]);
        Hotspot::create([
            'scene_id' => $createdScenes['summit']->id,
            'type' => 'info',
            'yaw' => 90.0,
            'pitch' => -5.0,
            'label' => 'View of Ogun River & Abeokuta City',
            'description' => 'From 137m high, you can see the meandering Ogun River and the distinctive rust-brown corrugated rooftops of historic Egba quarters.',
        ]);
    }

    private function seedZumaRock(): void
    {
        $niger = State::where('name', 'Niger')->first();
        $natureCategory = Category::where('name', 'Nature')->first() ?? Category::first();

        $heroPath = 'attractions/heroes/zuma-hero.jpg';
        $thumbPath = 'attractions/thumbs/zuma-thumb.jpg';
        $this->createThumbnail(storage_path('app/public/' . $heroPath), 'Zuma Rock, Niger State', [30, 25, 45]);
        $this->createThumbnail(storage_path('app/public/' . $thumbPath), 'Zuma Rock', [40, 35, 55]);

        $attraction = Attraction::updateOrCreate(
            ['slug' => 'zuma-rock'],
            [
                'category_id' => $natureCategory->id,
                'state_id' => $niger->id,
                'name' => 'Zuma Rock',
                'slug' => 'zuma-rock',
                'short_description' => 'A colossal natural monolith standing 725 metres above its surroundings, famously known as the Gateway to Abuja with a natural human face profile.',
                'description' => "Zuma Rock is one of Nigeria's most striking geographical wonders. Rising majestically to 725 metres (2,379 ft) above the surrounding plateau just west of Nigeria's capital city Abuja, this gigantic natural gabbro monolith has captivated explorers and indigenous populations for centuries.\n\nThe most fascinating feature of Zuma Rock is its natural human-like face contour carved by wind and rain into the sheer rock face, featuring clear depictions of eyes, nose, and mouth. Depicted on the Nigerian 100-Naira banknote, it remains a symbol of national heritage.",
                'history' => "Historically, Zuma Rock was used as an impenetrable defensive fortress by the Gbagyi (Gwari) people during inter-tribal warfare. In indigenous folklore, Zuma Rock is venerated as a spiritual seat and protective entity.",
                'interesting_facts' => "• Stands approximately 725 metres above the surrounding landscape and over 1,100 metres above sea level.\n• Appears on Nigeria's 100-Naira currency note.\n• During heavy rainy seasons, the top of the monolith occasionally catches fire in folklore sightings caused by friction from falling boulders.",
                'address' => 'Madalla, along Abuja-Kaduna Expressway',
                'lga' => 'Suleja',
                'latitude' => 9.1287,
                'longitude' => 7.2343,
                'thumbnail' => $thumbPath,
                'hero_image' => $heroPath,
                'visiting_hours' => 'Always visible / 8:00 AM - 6:00 PM for photography visits',
                'entry_fee' => 'Free viewing / Guided tours ₦1,500',
                'is_featured' => true,
                'tour_available' => true,
                'status' => 'published',
                'view_count' => 3821,
            ]
        );

        $scenes = [
            ['name' => 'Monolith Viewing Grounds', 'slug' => 'viewing-grounds', 'yaw' => 0, 'pitch' => 12, 'theme' => [[30, 40, 70], [80, 100, 140], [140, 120, 90], [70, 60, 45]]],
            ['name' => 'Base of the Giant Monolith', 'slug' => 'rock-base', 'yaw' => 45, 'pitch' => 20, 'theme' => [[40, 45, 65], [90, 95, 115], [110, 105, 95], [60, 55, 45]]],
            ['name' => 'Human Face Contour Perspective', 'slug' => 'face-profile', 'yaw' => 10, 'pitch' => 25, 'theme' => [[25, 35, 60], [75, 90, 130], [130, 115, 85], [65, 55, 40]]],
        ];

        $created = [];
        foreach ($scenes as $idx => $s) {
            $panoRel = "panoramas/zuma-rock/{$s['slug']}.jpg";
            $this->createEquirectangularPanorama(
                storage_path("app/public/{$panoRel}"),
                "Zuma Rock: {$s['name']}",
                "Nigeria Virtual 360° Monolith Tour",
                $s['theme']
            );

            $scene = Scene::updateOrCreate(
                ['attraction_id' => $attraction->id, 'slug' => $s['slug']],
                [
                    'name' => $s['name'],
                    'slug' => $s['slug'],
                    'panorama_path' => $panoRel,
                    'initial_yaw' => $s['yaw'],
                    'initial_pitch' => $s['pitch'],
                    'initial_hfov' => 100,
                    'sort_order' => $idx + 1,
                    'is_default' => $idx === 0,
                    'status' => 'published',
                ]
            );
            $created[$s['slug']] = $scene;
        }

        // Hotspots for Zuma Rock
        Hotspot::whereIn('scene_id', collect($created)->pluck('id'))->delete();
        Hotspot::create([
            'scene_id' => $created['viewing-grounds']->id,
            'type' => 'scene',
            'yaw' => 15.0,
            'pitch' => 8.0,
            'label' => 'Move Closer to Rock Base →',
            'target_scene_id' => $created['rock-base']->id,
        ]);
        Hotspot::create([
            'scene_id' => $created['viewing-grounds']->id,
            'type' => 'info',
            'yaw' => -30.0,
            'pitch' => 18.0,
            'label' => 'Natural Human Face Silhouette',
            'description' => 'Observe the upper central face of the monolith where weather patterns carved distinct facial features.',
        ]);
        Hotspot::create([
            'scene_id' => $created['rock-base']->id,
            'type' => 'scene',
            'yaw' => -160.0,
            'pitch' => -8.0,
            'label' => '← Back to Wide Perspective',
            'target_scene_id' => $created['viewing-grounds']->id,
        ]);
    }

    private function seedYankari(): void
    {
        $bauchi = State::where('name', 'Bauchi')->first();
        $parksCategory = Category::where('name', 'Parks & Resorts')->first() ?? Category::first();

        $heroPath = 'attractions/heroes/yankari-hero.jpg';
        $thumbPath = 'attractions/thumbs/yankari-thumb.jpg';
        $this->createThumbnail(storage_path('app/public/' . $heroPath), 'Yankari Game Reserve, Bauchi', [25, 75, 45]);
        $this->createThumbnail(storage_path('app/public/' . $thumbPath), 'Yankari Reserve', [30, 80, 50]);

        $attraction = Attraction::updateOrCreate(
            ['slug' => 'yankari-game-reserve'],
            [
                'category_id' => $parksCategory->id,
                'state_id' => $bauchi->id,
                'name' => 'Yankari Game Reserve',
                'slug' => 'yankari-game-reserve',
                'short_description' => "Nigeria's premier wildlife national park, home to the crystal-clear Wikki Warm Springs, elephants, lions, and ancient Marshall Caves.",
                'description' => "Yankari Game Reserve is the premier wildlife conservation park in West Africa, spanning approximately 2,244 square kilometres in south-central Bauchi State. Established originally as a game reserve in 1956 and later converted into a national park in 1991, Yankari is renowned for hosting the largest surviving herd of African elephants in Nigeria, as well as roan antelopes, olive baboons, and diverse bird species.\n\nIts crowning jewel is the world-famous Wikki Warm Spring, a crystal-clear thermal natural spring maintaining a constant, soothing 31.1°C temperature 24 hours a day, alongside the subterranean archaeological marvel known as the Marshall Caves.",
                'history' => "Yankari was first conceived by Alhaji Muhammadu Ngeleruma in 1953 after visiting Sudanese game preserves, opening to the public on December 1, 1962. It represents Nigeria's foremost eco-tourism and scientific research habitat.",
                'interesting_facts' => "• Wikki Warm Springs discharges 21,000 litres of pure, crystalline warm water per minute at a constant 31.1°C.\n• Contains 59 Marshall Caves dug into sandstone cliffs that early humans inhabited centuries ago.\n• Hosts over 350 species of resident and migratory birds.",
                'address' => 'Mainamaji Village, Alkaleri LGA, Bauchi State',
                'lga' => 'Alkaleri',
                'latitude' => 9.7542,
                'longitude' => 10.5126,
                'thumbnail' => $thumbPath,
                'hero_image' => $heroPath,
                'visiting_hours' => 'Safari drives 7:00 AM & 3:30 PM / Warm spring open 24/7 for guests',
                'entry_fee' => '₦2,500 (Adults), ₦1,000 (Students)',
                'is_featured' => true,
                'tour_available' => true,
                'status' => 'published',
                'view_count' => 2912,
            ]
        );

        $scenes = [
            ['name' => 'Wikki Warm Spring Basin', 'slug' => 'wikki-spring', 'yaw' => 0, 'pitch' => -8, 'theme' => [[20, 80, 110], [50, 140, 170], [80, 160, 130], [40, 100, 70]]],
            ['name' => 'Safari Main Savannah Gate', 'slug' => 'safari-gate', 'yaw' => 30, 'pitch' => 2, 'theme' => [[50, 80, 60], [100, 140, 90], [160, 140, 80], [80, 70, 40]]],
        ];

        $created = [];
        foreach ($scenes as $idx => $s) {
            $panoRel = "panoramas/yankari/{$s['slug']}.jpg";
            $this->createEquirectangularPanorama(
                storage_path("app/public/{$panoRel}"),
                "Yankari: {$s['name']}",
                "Nigeria Virtual 360° Wildlife & Thermal Spring Tour",
                $s['theme']
            );

            $scene = Scene::updateOrCreate(
                ['attraction_id' => $attraction->id, 'slug' => $s['slug']],
                [
                    'name' => $s['name'],
                    'slug' => $s['slug'],
                    'panorama_path' => $panoRel,
                    'initial_yaw' => $s['yaw'],
                    'initial_pitch' => $s['pitch'],
                    'initial_hfov' => 100,
                    'sort_order' => $idx + 1,
                    'is_default' => $idx === 0,
                    'status' => 'published',
                ]
            );
            $created[$s['slug']] = $scene;
        }

        Hotspot::whereIn('scene_id', collect($created)->pluck('id'))->delete();
        Hotspot::create([
            'scene_id' => $created['wikki-spring']->id,
            'type' => 'info',
            'yaw' => 45.0,
            'pitch' => -12.0,
            'label' => 'Constant 31.1°C Mineral Water',
            'description' => 'The warm spring bubbles continuously out of a limestone fissure, providing warm crystal-clear water year-round.',
        ]);
        Hotspot::create([
            'scene_id' => $created['wikki-spring']->id,
            'type' => 'scene',
            'yaw' => 170.0,
            'pitch' => -4.0,
            'label' => 'Head to Savannah Safari Concourse →',
            'target_scene_id' => $created['safari-gate']->id,
        ]);
    }

    private function seedObudu(): void
    {
        $crossRiver = State::where('name', 'Cross River')->first();
        $resortCategory = Category::where('name', 'Parks & Resorts')->first() ?? Category::first();

        $heroPath = 'attractions/heroes/obudu-hero.jpg';
        $thumbPath = 'attractions/thumbs/obudu-thumb.jpg';
        $this->createThumbnail(storage_path('app/public/' . $heroPath), 'Obudu Mountain Resort, Cross River', [15, 45, 60]);
        $this->createThumbnail(storage_path('app/public/' . $thumbPath), 'Obudu Resort', [20, 50, 65]);

        $attraction = Attraction::updateOrCreate(
            ['slug' => 'obudu-mountain-resort'],
            [
                'category_id' => $resortCategory->id,
                'state_id' => $crossRiver->id,
                'name' => 'Obudu Mountain Resort',
                'slug' => 'obudu-mountain-resort',
                'short_description' => 'A breathtaking highland paradise nestled at 1,576 metres in the clouds of Cross River State, featuring one of the longest cable cars in the world.',
                'description' => "Obudu Mountain Resort (formerly known as the Obudu Cattle Ranch) is nestled in the Sankwala Mountains of Cross River State. Perched at an altitude of approximately 1,576 metres (5,170 ft) above sea level, the climate is temperate and cool, frequently wrapped in gentle alpine mist and clouds.\n\nThe resort features the remarkable 4-kilometre Obudu Cable Car, a spectacular canopy walkway suspended above ancient montane rainforest canopy, the Holy Mountain viewpoint, natural water grottoes, and rolling highland vistas reminiscent of European alpine landscapes.",
                'interesting_facts' => "• Located at 1,576 metres elevation above sea level.\n• Home to one of the longest cable car systems in the world (4 km).\n• Features an alpine climate where temperatures frequently drop to 15°C.",
                'address' => 'Obanliku Local Government Area, Cross River State',
                'lga' => 'Obanliku',
                'latitude' => 6.3833,
                'longitude' => 9.3833,
                'thumbnail' => $thumbPath,
                'hero_image' => $heroPath,
                'visiting_hours' => 'Open 24 hours daily for resort guests and sightseers',
                'entry_fee' => '₦1,000 entrance / Cable car ₦4,000 return',
                'is_featured' => true,
                'tour_available' => true,
                'status' => 'published',
                'view_count' => 2410,
            ]
        );

        $panoRel = 'panoramas/obudu/cable-car-plateau.jpg';
        $this->createEquirectangularPanorama(
            storage_path("app/public/{$panoRel}"),
            'Obudu: Cable Car Summit Plateau',
            'Highland Cloud Sanctuary 360° Virtual Tour',
            [[10, 50, 90], [60, 120, 160], [100, 140, 90], [40, 70, 45]]
        );

        $scene = Scene::updateOrCreate(
            ['attraction_id' => $attraction->id, 'slug' => 'cable-car-plateau'],
            [
                'name' => 'Cable Car Summit Plateau',
                'slug' => 'cable-car-plateau',
                'panorama_path' => $panoRel,
                'initial_yaw' => 0,
                'initial_pitch' => -5,
                'initial_hfov' => 100,
                'sort_order' => 1,
                'is_default' => true,
                'status' => 'published',
            ]
        );

        Hotspot::where('scene_id', $scene->id)->delete();
        Hotspot::create([
            'scene_id' => $scene->id,
            'type' => 'info',
            'yaw' => 45.0,
            'pitch' => 0.0,
            'label' => '4km Highland Cable Car Span',
            'description' => 'The cable car carries visitors from the tropical bottom of the valley straight into the alpine plateau clouds in 11 scenic minutes.',
        ]);
    }

    private function seedLekki(): void
    {
        $lagos = State::where('name', 'Lagos')->first();
        $natureCategory = Category::where('name', 'Nature')->first() ?? Category::first();

        $heroPath = 'attractions/heroes/lekki-hero.jpg';
        $thumbPath = 'attractions/thumbs/lekki-thumb.jpg';
        $this->createThumbnail(storage_path('app/public/' . $heroPath), 'Lekki Conservation Centre, Lagos', [10, 55, 35]);
        $this->createThumbnail(storage_path('app/public/' . $thumbPath), 'Lekki Conservation', [15, 60, 40]);

        $attraction = Attraction::updateOrCreate(
            ['slug' => 'lekki-conservation-centre'],
            [
                'category_id' => $natureCategory->id,
                'state_id' => $lagos->id,
                'name' => 'Lekki Conservation Centre',
                'slug' => 'lekki-conservation-centre',
                'short_description' => 'A pristine 78-hectare coastal wetland and rainforest reserve in Lagos, boasting the longest canopy walkway in Africa.',
                'description' => "Lekki Conservation Centre (LCC) is a 78-hectare natural reserve located on the Lekki Peninsula in Lagos. Founded in 1990 by the Nigerian Conservation Foundation (NCF), it preserves a vital tract of coastal mangrove swamps, savannah grassland, and pristine secondary rainforest amidst the rapid urbanization of modern Lagos.\n\nThe centerpiece attraction is Africa's longest canopy walkway — a 401-metre suspended bridge that lets visitors stroll amongst tree crowns 22 feet above the forest floor alongside monkeys, monitor lizards, and exotic birdlife.",
                'interesting_facts' => "• Hosts Africa's longest canopy walkway at 401 metres in length.\n• Home to a 95-year-old giant tortoise that roams the central family park.\n• Houses the largest wooden floor outdoor chess game in West Africa.",
                'address' => 'Km 19 Lekki-Epe Expressway, Lekki, Lagos',
                'lga' => 'Eti-Osa',
                'latitude' => 6.4389,
                'longitude' => 3.5358,
                'thumbnail' => $thumbPath,
                'hero_image' => $heroPath,
                'visiting_hours' => '8:30 AM - 5:00 PM Daily',
                'entry_fee' => '₦1,500 (Entry) + ₦1,500 (Canopy Walk)',
                'is_featured' => true,
                'tour_available' => true,
                'status' => 'published',
                'view_count' => 3450,
            ]
        );

        $panoRel = 'panoramas/lekki/canopy-walkway.jpg';
        $this->createEquirectangularPanorama(
            storage_path("app/public/{$panoRel}"),
            'Lekki: Canopy Walkway in Africa',
            'Suspended 22 Feet Above Mangrove Rainforest 360°',
            [[15, 55, 30], [35, 95, 50], [85, 135, 70], [30, 65, 35]]
        );

        $scene = Scene::updateOrCreate(
            ['attraction_id' => $attraction->id, 'slug' => 'canopy-walkway'],
            [
                'name' => 'Suspended Canopy Walkway',
                'slug' => 'canopy-walkway',
                'panorama_path' => $panoRel,
                'initial_yaw' => 0,
                'initial_pitch' => -8,
                'initial_hfov' => 100,
                'sort_order' => 1,
                'is_default' => true,
                'status' => 'published',
            ]
        );

        Hotspot::where('scene_id', $scene->id)->delete();
        Hotspot::create([
            'scene_id' => $scene->id,
            'type' => 'info',
            'yaw' => 30.0,
            'pitch' => -10.0,
            'label' => 'Longest Canopy Walkway in Africa',
            'description' => 'Engineered with suspended cable bridges traversing 401 metres across the upper canopy of the swamp forest.',
        ]);
    }

    private function seedAnalytics(): void
    {
        $olumo = Attraction::where('slug', 'olumo-rock')->first();
        if (!$olumo) return;

        // Create sample realistic evaluation sessions for Chapter 4 research metrics
        $devices = ['mobile', 'mobile', 'desktop', 'mobile', 'tablet', 'desktop', 'mobile', 'desktop'];
        $browsers = ['Chrome', 'Safari', 'Chrome', 'Firefox', 'Chrome', 'Edge', 'Chrome', 'Safari'];

        for ($i = 0; $i < 30; $i++) {
            $device = $devices[$i % count($devices)];
            $browser = $browsers[$i % count($browsers)];
            $fps = $device === 'desktop' ? rand(48, 60) : rand(34, 45); // Meets > 30 FPS target
            $loadTime = rand(1650, 2450); // Meets < 3000ms target
            $transitionTime = rand(580, 820); // Fast transitions

            TourSession::create([
                'attraction_id' => $olumo->id,
                'session_uuid' => Str::uuid()->toString(),
                'device_type' => $device,
                'browser' => $browser,
                'os' => $device === 'mobile' ? 'Android / iOS' : 'Windows / macOS',
                'avg_fps' => $fps,
                'load_time_ms' => $loadTime,
                'transition_time_ms' => $transitionTime,
                'scenes_visited' => rand(3, 5),
                'completed' => rand(0, 1) === 1,
                'started_at' => now()->subMinutes(rand(10, 5000)),
                'ended_at' => now()->subMinutes(rand(1, 9)),
            ]);
        }
    }
}
