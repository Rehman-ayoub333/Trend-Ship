const THEMES_DATA = [
  {
    slug: 'boost',
    name: 'BOOST',
    number: '01',
    tagline: 'Warmth that propels you forward',
    description: 'Where comfort becomes a creative force. BOOST is about tactile richness, amber warmth, and interiors that feel like a deep breath.',
    keywords: ['COMFORT', 'WARMTH', 'VITALITY', 'BOLD', 'ORGANIC'],
    color_bg: '#c8a060',
    color_text: '#1a1614',
    color_accent: '#e8c898',
    materials: ['boost-01', 'boost-02', 'boost-03'],
    lookbook_spreads: [1, 2],
    exhibition_rooms: ['room-01']
  },
  {
    slug: 'cosmos',
    name: 'COSMOS',
    number: '02',
    tagline: 'Depth that holds the infinite',
    description: 'The quiet of deep space translated into interior language. COSMOS is navy, depth, mystery, and the feeling of standing under stars.',
    keywords: ['SPACE', 'DEPTH', 'MYSTERY', 'INFINITE', 'SERENE'],
    color_bg: '#0a0e1e',
    color_text: '#c8d8f0',
    color_accent: '#3a5a9a',
    materials: ['cosmos-01', 'cosmos-02', 'cosmos-03'],
    lookbook_spreads: [3, 4],
    exhibition_rooms: ['room-02']
  },
  {
    slug: 'ooparts',
    name: 'OOPARTS',
    number: '03',
    tagline: 'Time as material',
    description: 'Out-of-place artifacts — the strange beauty of things that should not exist yet do. OOPARTS embraces the anachronistic, the textured, the ancient-feeling in new form.',
    keywords: ['TIME', 'ARTIFACT', 'MEMORY', 'LINE', 'TEXTURE'],
    color_bg: '#8a6840',
    color_text: '#f0e8d8',
    color_accent: '#c8a870',
    materials: ['ooparts-01', 'ooparts-02', 'ooparts-03'],
    lookbook_spreads: [5, 6],
    exhibition_rooms: ['room-03']
  },
  {
    slug: 'synergy',
    name: 'SYNERGY',
    number: '04',
    tagline: 'New energy that flourishes when we come together',
    description: 'SY(E)NERGY — a portmanteau of Synergy and Energy. When different values meet and fuse, something entirely new is born. This is the central theme. The heart of Trendship 2025.',
    keywords: ['FUSION', 'ENERGY', 'TOGETHER', 'FLOW', 'BLOOM'],
    color_bg: '#d4c4bc',
    color_text: '#0a0a0a',
    color_accent: '#d4857a',
    materials: ['synergy-01', 'synergy-02', 'synergy-03'],
    lookbook_spreads: [7, 8],
    exhibition_rooms: ['room-04']
  }
];

const MATERIALS_DATA = [
  {
    id: 'boost-01',
    name: 'Terracotta Linen',
    code: 'LX-B2025-001',
    theme: 'boost',
    surface: 'Matte Textured',
    dimensions: '1220 × 2440mm',
    thickness: '6mm',
    finish: 'Anti-fingerprint',
    colors: ['#c8885a', '#b87848', '#d8a880', '#a86840'],
    defaultColor: '#c8885a',
    description: 'A warm terracotta-toned surface with linen-like texture. Brings organic warmth to kitchen and bath surfaces.',
    imageUrl: 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800',
    textureUrl: 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400',
    roomUrl: 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=1200',
    application: ['Kitchen', 'Bath', 'Living'],
    featured: true
  },
  // Add other materials here following the same structure (3 per theme)
];

window.THEMES_DATA = THEMES_DATA;
window.MATERIALS_DATA = MATERIALS_DATA;
