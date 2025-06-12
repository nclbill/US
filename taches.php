<?php

// Inclusion des fichiers nécessaires à l'initialisation et à la préparation de la page
require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';

// Vérification de la sécurité de la page
if (!securePage($_SERVER['PHP_SELF'])) {
    die("Accès interdit !");
}


$user_id = $user->data()->id;

// Vérifie si l'utilisateur est admin (adapter selon ta gestion des rôles)
$isAdmin = hasPerm([2], $user_id); // exemple: permission 2 = admin

// Traitement POST pour ajout tâche
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['add_task'])) {
    // Récupérer et valider les données
    $assigner_id = $user_id;
    $user_assigne = intval($_POST['user_id']);
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $date_echeance = $_POST['date_echeance'] ?: null;
    $frequence = $_POST['frequence'] ?? 'ponctuelle';
    $priorite = $_POST['priorite'] ?? 'moyenne';
    $rappel = isset($_POST['rappel']) ? 1 : 0;

    if ($titre && $user_assigne) {
      $db->query("INSERT INTO tasks (user_id, assigner_id, titre, description, date_echeance, frequence, priorite, rappel) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
        [$user_assigne, $assigner_id, $titre, $description, $date_echeance, $frequence, $priorite, $rappel]);
      echo "<div class='success'>Tâche ajoutée avec succès.</div>";
    } else {
      echo "<div class='error'>Veuillez remplir au minimum le titre et l’utilisateur assigné.</div>";
    }
  }

  if (isset($_POST['add_meeting']) && $isAdmin) {
    // Récupérer et valider réunion
    $titre = trim($_POST['meeting_titre']);
    $description = trim($_POST['meeting_description']);
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'] ?: null;
    $participants = $_POST['participants'] ?? [];
    $participants_str = implode(',', array_map('intval', $participants));
    $createur_id = $user_id;

    if ($titre && $date_debut) {
      $db->query("INSERT INTO meetings (titre, description, date_debut, date_fin, createur_id, participants) VALUES (?, ?, ?, ?, ?, ?)",
        [$titre, $description, $date_debut, $date_fin, $createur_id, $participants_str]);
      echo "<div class='success'>Réunion ajoutée avec succès.</div>";
    } else {
      echo "<div class='error'>Veuillez remplir au minimum le titre et la date de début.</div>";
    }
  }
}

// Récupérer les tâches assignées ou créées par l'utilisateur
$tasks = $db->query("SELECT t.*, u.email AS user_email, a.email AS assigner_email
                     FROM tasks t
                     LEFT JOIN users u ON t.user_id = u.id
                     LEFT JOIN users a ON t.assigner_id = a.id
                     WHERE t.user_id = ? OR t.assigner_id = ?
                     ORDER BY t.date_echeance ASC", [$user_id, $user_id])->results();

// Récupérer réunions où l'utilisateur est participant ou créateur
$meetings = $db->query("SELECT * FROM meetings WHERE FIND_IN_SET(?, participants) > 0 OR createur_id = ? ORDER BY date_debut ASC", [$user_id, $user_id])->results();

// Construire les événements pour FullCalendar
$events = [];
foreach ($tasks as $t) {
  $color = 'blue';
  if ($t->priorite === 'haute') $color = 'red';
  elseif ($t->priorite === 'basse') $color = 'gray';

  $events[] = [
    'title' => 'Tâche: ' . $t->titre,
    'start' => $t->date_echeance,
    'color' => $color,
    'url' => 'edit_task.php?id=' . $t->id,
  ];
}
foreach ($meetings as $m) {
  $events[] = [
    'title' => 'Réunion: ' . $m->titre,
    'start' => $m->date_debut,
    'end' => $m->date_fin,
    'color' => 'green',
    'url' => 'edit_meeting.php?id=' . $m->id,
  ];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des Tâches et Réunions - Espace Moto</title>
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.css' rel='stylesheet' />
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f7f7f7; }
    .container { max-width: 1000px; margin: auto; background: #fff; padding: 20px; border-radius: 5px; }
    h1 { text-align: center; }
    form { margin-bottom: 30px; padding: 15px; background: #e9e9e9; border-radius: 4px; }
    form > div { margin-bottom: 10px; }
    label { display: block; margin-bottom: 4px; font-weight: bold; }
    input, select, textarea { width: 100%; padding: 6px; box-sizing: border-box; }
    .success { padding: 10px; background: #c8e6c9; color: #2e7d32; margin-bottom: 10px; border-radius: 3px; }
    .error { padding: 10px; background: #ffcdd2; color: #c62828; margin-bottom: 10px; border-radius: 3px; }
    #calendar { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 5px; }
  </style>
</head>
<body>
<div class="container">
  <h1>Gestion des Tâches et Réunions</h1>

  <!-- Formulaire d'ajout de tâche -->
  <form method="post">
    <h2>Ajouter une tâche</h2>
    <div>
      <label for="user_id">Assigner à :</label>
      <select name="user_id" id="user_id" required>
        <option value="">-- Choisir un utilisateur --</option>
        <?php
        // Récupérer tous les utilisateurs (hors clients éventuels, adapter selon ta table users)
        $users = $db->query("SELECT id, email FROM users ORDER BY email")->results();
        foreach ($users as $u) {
          echo "<option value='" . htmlspecialchars($u->id) . "'>" . htmlspecialchars($u->email) . "</option>";
        }
        ?>
      </select>
    </div>
    <div>
      <label for="titre">Titre de la tâche :</label>
      <input type="text" name="titre" id="titre" required>
    </div>
    <div>
      <label for="description">Description :</label>
      <textarea name="description" id="description"></textarea>
    </div>
    <div>
      <label for="date_echeance">Date d'échéance :</label>
      <input type="datetime-local" name="date_echeance" id="date_echeance">
    </div>
    <div>
      <label for="frequence">Fréquence :</label>
      <select name="frequence" id="frequence">
        <option value="ponctuelle">Ponctuelle</option>
        <option value="journaliere">Journalière</option>
        <option value="mensuelle">Mensuelle</option>
        <option value="annuelle">Annuelle</option>
      </select>
    </div>
    <div>
      <label for="priorite">Priorité :</label>
      <select name="priorite" id="priorite">
        <option value="moyenne" selected>Moyenne</option>
        <option value="basse">Basse</option>
        <option value="haute">Haute</option>
      </select>
    </div>
    <div>
      <label><input type="checkbox" name="rappel"> Activer rappel</label>
    </div>
    <button type="submit" name="add_task">Ajouter la tâche</button>
  </form>

  <!-- Formulaire d'ajout de réunion (uniquement admin) -->
  <?php if ($isAdmin): ?>
  <form method="post">
    <h2>Ajouter une réunion</h2>
    <div>
      <label for="meeting_titre">Titre :</label>
      <input type="text" name="meeting_titre" id="meeting_titre" required>
    </div>
    <div>
      <label for="meeting_description">Description :</label>
      <textarea name="meeting_description" id="meeting_description"></textarea>
    </div>
    <div>
      <label for="date_debut">Date et heure début :</label>
      <input type="datetime-local" name="date_debut" id="date_debut" required>
    </div>
    <div>
      <label for="date_fin">Date et heure fin :</label>
      <input type="datetime-local" name="date_fin" id="date_fin">
    </div>
    <div>
      <label for="participants">Participants :</label>
      <select name="participants[]" id="participants" multiple size="5">
        <?php
        foreach ($users as $u) {
          echo "<option value='" . htmlspecialchars($u->id) . "'>" . htmlspecialchars($u->email) . "</option>";
        }
        ?>
      </select>
      <small>Pour sélectionner plusieurs : Ctrl + clic (Cmd + clic sur Mac)</small>
    </div>
    <button type="submit" name="add_meeting">Ajouter la réunion</button>
  </form>
  <?php endif; ?>

  <!-- Calendrier FullCalendar -->
  <div id='calendar'></div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      events: <?= json_encode($events) ?>,
      eventClick: function(info) {
        if (info.event.url) {
          window.open(info.event.url, '_blank');
          info.jsEvent.preventDefault();
        }
      }
    });

    calendar.render();
  });
</script>
</body>
</html>
<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
