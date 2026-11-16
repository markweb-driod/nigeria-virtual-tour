// Real photographs of each attraction from Wikimedia Commons, shown as partial
// panoramas. haov/vaov are the horizontal/vertical angles (degrees) the photo
// covers, so Pannellum can map it without a fake 360° wrap.
// Generated from the Commons file pages; keep credits when replacing images.

export type PanoramaCredit = {
  title: string;
  author: string;
  license: string;
  licenseUrl: string;
  source: string;
};

export type PanoramaMeta = {
  haov: number;
  vaov: number;
  credit: PanoramaCredit;
};

const PANORAMAS: Record<string, PanoramaMeta> = {
  "panoramas/olumo-rock/main-entrance.jpg": {
    "haov": 130,
    "vaov": 51.32,
    "credit": {
      "title": "Olumo Rock, Abeokuta.jpg",
      "author": "Adedayo Adesilola",
      "license": "CC BY-SA 4.0",
      "licenseUrl": "https://creativecommons.org/licenses/by-sa/4.0",
      "source": "https://commons.wikimedia.org/wiki/File:Olumo_Rock,_Abeokuta.jpg"
    }
  },
  "panoramas/olumo-rock/lower-platform.jpg": {
    "haov": 65,
    "vaov": 48.75,
    "credit": {
      "title": "Olumo Rock Abk.7.jpg",
      "author": "F.Samuel95",
      "license": "CC BY-SA 4.0",
      "licenseUrl": "https://creativecommons.org/licenses/by-sa/4.0",
      "source": "https://commons.wikimedia.org/wiki/File:Olumo_Rock_Abk.7.jpg"
    }
  },
  "panoramas/olumo-rock/cave-entrance.jpg": {
    "haov": 65,
    "vaov": 48.75,
    "credit": {
      "title": "Hide out cave during 19th century Egba war.jpg",
      "author": "ABORISADEADETONA",
      "license": "CC BY-SA 4.0",
      "licenseUrl": "https://creativecommons.org/licenses/by-sa/4.0",
      "source": "https://commons.wikimedia.org/wiki/File:Hide_out_cave_during_19th_century_Egba_war.jpg"
    }
  },
  "panoramas/olumo-rock/cave-interior.jpg": {
    "haov": 70,
    "vaov": 46.67,
    "credit": {
      "title": "Olumo Shrine.jpg",
      "author": "Akidek",
      "license": "CC BY-SA 4.0",
      "licenseUrl": "https://creativecommons.org/licenses/by-sa/4.0",
      "source": "https://commons.wikimedia.org/wiki/File:Olumo_Shrine.jpg"
    }
  },
  "panoramas/olumo-rock/summit.jpg": {
    "haov": 70,
    "vaov": 44.13,
    "credit": {
      "title": "Abeokutafromolumorock.jpg",
      "author": "Melvin \"Buddy\" Baker",
      "license": "CC BY 2.0",
      "licenseUrl": "https://creativecommons.org/licenses/by/2.0",
      "source": "https://commons.wikimedia.org/wiki/File:Abeokutafromolumorock.jpg"
    }
  },
  "panoramas/zuma-rock/viewing-grounds.jpg": {
    "haov": 70,
    "vaov": 46.67,
    "credit": {
      "title": "ZUMA ROCK.jpg",
      "author": "Nzenwa Chinomso",
      "license": "CC BY-SA 4.0",
      "licenseUrl": "https://creativecommons.org/licenses/by-sa/4.0",
      "source": "https://commons.wikimedia.org/wiki/File:ZUMA_ROCK.jpg"
    }
  },
  "panoramas/zuma-rock/rock-base.jpg": {
    "haov": 75,
    "vaov": 42.19,
    "credit": {
      "title": "Zuma Rock - Niger state 01.jpg",
      "author": "Emmyk10",
      "license": "CC BY-SA 4.0",
      "licenseUrl": "https://creativecommons.org/licenses/by-sa/4.0",
      "source": "https://commons.wikimedia.org/wiki/File:Zuma_Rock_-_Niger_state_01.jpg"
    }
  },
  "panoramas/zuma-rock/face-profile.jpg": {
    "haov": 65,
    "vaov": 48.75,
    "credit": {
      "title": "Zuma Rock in the dry season.jpg",
      "author": "Hui Linbo",
      "license": "CC BY-SA 4.0",
      "licenseUrl": "https://creativecommons.org/licenses/by-sa/4.0",
      "source": "https://commons.wikimedia.org/wiki/File:Zuma_Rock_in_the_dry_season.jpg"
    }
  },
  "panoramas/yankari/wikki-spring.jpg": {
    "haov": 75,
    "vaov": 42.19,
    "credit": {
      "title": "Wikki Warm Spring Lake.jpg",
      "author": "Ikani",
      "license": "CC BY-SA 4.0",
      "licenseUrl": "https://creativecommons.org/licenses/by-sa/4.0",
      "source": "https://commons.wikimedia.org/wiki/File:Wikki_Warm_Spring_Lake.jpg"
    }
  },
  "panoramas/yankari/safari-gate.jpg": {
    "haov": 75,
    "vaov": 42.21,
    "credit": {
      "title": "2nd Gate of Yankari Game Reserve 02.jpg",
      "author": "Kambai Akau",
      "license": "CC BY-SA 4.0",
      "licenseUrl": "https://creativecommons.org/licenses/by-sa/4.0",
      "source": "https://commons.wikimedia.org/wiki/File:2nd_Gate_of_Yankari_Game_Reserve_02.jpg"
    }
  },
  "panoramas/obudu/cable-car-plateau.jpg": {
    "haov": 180,
    "vaov": 39.94,
    "credit": {
      "title": "OBUDU MOUNTAINS.jpg",
      "author": "Laminartosin",
      "license": "CC BY-SA 4.0",
      "licenseUrl": "https://creativecommons.org/licenses/by-sa/4.0",
      "source": "https://commons.wikimedia.org/wiki/File:OBUDU_MOUNTAINS.jpg"
    }
  },
  "panoramas/lekki/canopy-walkway.jpg": {
    "haov": 60,
    "vaov": 48.61,
    "credit": {
      "title": "Walking above Trees.jpg",
      "author": "Dayoakinbode",
      "license": "CC BY-SA 4.0",
      "licenseUrl": "https://creativecommons.org/licenses/by-sa/4.0",
      "source": "https://commons.wikimedia.org/wiki/File:Walking_above_Trees.jpg"
    }
  }
};

export function getPanoramaMeta(panoramaPath: string | undefined | null): PanoramaMeta | undefined {
  if (!panoramaPath) return undefined;
  const key = panoramaPath.replace(/^\/?(storage\/)?/, "");
  return PANORAMAS[key];
}
