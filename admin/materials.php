<?php
require_once dirname(__DIR__) . '/api/helpers.php';
requireAdmin();
$pdo       = getDB();
$materials = $pdo->query("SELECT * FROM materials ORDER BY sort_order,id")->fetchAll();
$active = 'surfaces';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Surface Archive — TRENDSHIP</title>
<link rel="stylesheet" href="admin.css">
</head>
<body class="admin-body">
<?php require __DIR__ . '/includes/sidebar.php'; ?>
<main class="main">
  <div class="top-bar">
    <div>
      <div class="page-eyebrow">Curate · Surfaces</div>
      <div class="page-title">Surface Archive <span style="color:var(--muted);font-size:18px">(<?= count($materials) ?>)</span></div>
    </div>
    <button class="btn btn-compose" onclick="openModal()">+ Compose Surface</button>
  </div>

  <table>
    <thead>
      <tr>
        <th>Image</th><th>Name</th><th>Code</th><th>Theme</th>
        <th>Featured</th><th>Surface</th>        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($materials as $m): ?>
      <tr id="row-<?= $m['id'] ?>">
        <td><img class="mat-img" src="<?= htmlspecialchars($m['image_url']) ?>" alt=""></td>
        <td><?= htmlspecialchars($m['name']) ?></td>
        <td style="font-family:monospace;font-size:11px;color:#6a6058"><?= htmlspecialchars($m['code']) ?></td>
        <td><span class="theme-pill theme-<?= $m['theme'] ?>"><?= strtoupper($m['theme']) ?></span></td>
        <td><?= $m['featured'] ? '<span class="feat">★</span>' : '<span style="color:#2a2a2a">○</span>' ?></td>
        <td><?= htmlspecialchars($m['surface']) ?></td>
        <td style="display:flex;gap:8px">
          <button class="btn btn-refine"
            onclick='editMaterial(<?= htmlspecialchars(json_encode($m), ENT_QUOTES) ?>)'>Refine</button>
          <button class="btn btn-retire"
            onclick="deleteMaterial(<?= $m['id'] ?>, '<?= addslashes($m['name']) ?>')">Retire</button>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</main>

<!-- Add/Edit Modal -->
<div class="modal-bg" id="modalBg">
  <div class="modal">
    <h2 id="modal-title">Compose Surface</h2>
    <form id="matForm">
      <input type="hidden" id="f-id">
      <div class="form-row">
        <div class="form-group"><label>Name *</label><input id="f-name" required></div>
        <div class="form-group"><label>Code *</label><input id="f-code" placeholder="LX-B2025-001" required></div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Theme *</label>
          <select id="f-theme">
            <option value="boost">BOOST</option>
            <option value="cosmos">COSMOS</option>
            <option value="ooparts">OOPARTS</option>
            <option value="synergy">SYNERGY</option>
          </select>
        </div>
        <div class="form-group"><label>Surface *</label><input id="f-surface" required></div>
      </div>
      <div class="form-group"><label>Image URL *</label><input id="f-image" required></div>
      <div class="form-row">
        <div class="form-group"><label>Colour 1 (hex)</label><input id="f-c1" placeholder="#c8885a"></div>
        <div class="form-group"><label>Colour 2 (hex)</label><input id="f-c2" placeholder="#b87848"></div>
        <div class="form-group" style="grid-column:1/-1"><label>Colour 3 (hex)</label><input id="f-c3" placeholder="#d8a880"></div>
      </div>
      <div class="form-group"><label>Description</label><textarea id="f-desc"></textarea></div>
      <div class="form-row">
        <div class="form-group"><label>Finish</label><input id="f-finish"></div>
        <div class="form-group"><label>Thickness</label><input id="f-thick" placeholder="6mm"></div>
      </div>
      <div class="form-group">
        <label>Featured on Home Page</label>
        <select id="f-featured"><option value="0">No</option><option value="1">Yes</option></select>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-cancel" onclick="closeModal()">Step Back</button>
        <button type="submit" class="btn-preserve">Preserve</button>
      </div>
    </form>
  </div>
</div>

<div class="toast" id="toast"></div>

<script>
const API = '../api/materials/';
let editing = false;

function openModal(mat) {
  editing = false;
  document.getElementById('modal-title').textContent = 'Compose Surface';
  document.getElementById('matForm').reset();
  document.getElementById('f-id').value = '';
  document.getElementById('modalBg').classList.add('open');
}

function editMaterial(mat) {
  editing = true;
  document.getElementById('modal-title').textContent = 'Refine Surface';
  document.getElementById('f-id').value      = mat.id;
  document.getElementById('f-name').value    = mat.name;
  document.getElementById('f-code').value    = mat.code;
  document.getElementById('f-theme').value   = mat.theme;
  document.getElementById('f-surface').value = mat.surface;
  document.getElementById('f-image').value   = mat.image_url;
  document.getElementById('f-c1').value      = mat.color_1 || '';
  document.getElementById('f-c2').value      = mat.color_2 || '';
  document.getElementById('f-c3').value      = mat.color_3 || '';
  document.getElementById('f-desc').value    = mat.description || '';
  document.getElementById('f-finish').value  = mat.finish || '';
  document.getElementById('f-thick').value   = mat.thickness || '';
  document.getElementById('f-featured').value= mat.featured;
  document.getElementById('modalBg').classList.add('open');
}

function closeModal() {
  document.getElementById('modalBg').classList.remove('open');
}

document.getElementById('matForm').addEventListener('submit', async e => {
  e.preventDefault();
  const id = document.getElementById('f-id').value;
  const payload = {
    name: document.getElementById('f-name').value,
    code: document.getElementById('f-code').value,
    theme: document.getElementById('f-theme').value,
    surface: document.getElementById('f-surface').value,
    image_url: document.getElementById('f-image').value,
    color_1: document.getElementById('f-c1').value,
    color_2: document.getElementById('f-c2').value,
    color_3: document.getElementById('f-c3').value,
    description: document.getElementById('f-desc').value,
    finish: document.getElementById('f-finish').value,
    thickness: document.getElementById('f-thick').value,
    featured: parseInt(document.getElementById('f-featured').value),
  };

  const url    = editing ? API + 'update.php' : API + 'index.php';
  const method = editing ? 'PUT' : 'POST';
  if (editing) payload.id = parseInt(id);

  try {
    const res  = await fetch(url, { method, headers:{'Content-Type':'application/json'}, body: JSON.stringify(payload) });
    const data = await res.json();
    if (data.success) {
      showToast(data.message || 'Surface preserved.');
      closeModal();
      setTimeout(() => location.reload(), 1000);
    } else {
      showToast(data.error || 'Error saving material', true);
    }
  } catch {
    showToast('Network error', true);
  }
});

async function deleteMaterial(id, name) {
  if (!confirm(`Retire "${name}" from the archive? This cannot be undone.`)) return;
  try {
    const res  = await fetch(API + 'delete.php', {
      method: 'DELETE', headers:{'Content-Type':'application/json'},
      body: JSON.stringify({ id })
    });
    const data = await res.json();
    if (data.success) {
      document.getElementById('row-' + id)?.remove();
      showToast(data.message);
    } else {
      showToast(data.error, true);
    }
  } catch {
    showToast('Network error', true);
  }
}

function showToast(msg, err = false) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.style.borderColor = err ? '#c05050' : '#d4857a';
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 3000);
}

// Close modal on background click
document.getElementById('modalBg').addEventListener('click', e => {
  if (e.target === e.currentTarget) closeModal();
});
</script>
</body>
</html>
