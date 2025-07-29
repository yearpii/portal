<?php
session_start();
include '../includes/auth.php';
include '../includes/db.php';

$id_ukm = $_SESSION['id'];
$events = mysqli_query($conn, "SELECT * FROM events WHERE id_ukm = $id_ukm");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard UKM - Info UKM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4741A6;
            --secondary: #F9CE69;
            --accent: #9BBBFC;
            --background: #F8FAFC;
            --white: #FFFFFF;
            --black: #333333;
            --text: #2D3748;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }
        
        body {
            background-color: var(--background);
            color: var(--text);
        }
        
        .isi {
            padding-left: 2rem;
            padding-right: 2rem;
            padding-bottom: 2rem;
        }

        .header {
            padding: 1.5rem;
            background-color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        .page-title {
            font-size: 1.8rem;
            color: var(--primary);
            font-weight: 700;
        }
        
        .btn-group {
            display: flex;
            gap: 1rem;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: var(--white);
        }
        
        .btn-secondary {
            background-color: var(--white);
            color: var(--primary);
            border: 1px solid var(--primary);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .btn-primary:hover {
            background-color: #3a3485;
        }
        
        .btn-secondary:hover {
            background-color: var(--primary);
            color: var(--white);
        }
        
        .stat-card {
            background-color: var(--white);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-top: 4px solid var(--accent);
            max-width: 300px;
        }
        
        .stat-title {
            font-size: 0.9rem;
            color: #718096;
            margin-bottom: 0.5rem;
        }
        
        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .events-table {
            width: 100%;
            background-color: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #EDF2F7;
        }
        
        th {
            background-color: #F8FAFC;
            color: var(--primary);
            font-weight: 600;
        }
        
        .status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status.active {
            background-color: #E6FFFA;
            color: #38B2AC;
        }
        
        .status.upcoming {
            background-color: #EBF8FF;
            color: #4299E1;
        }
        
        .status.completed {
            background-color: #EDF2F7;
            color: #718096;
        }
        
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #EDF2F7;
            color: var(--primary);
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }
        
        .action-btn:hover {
            background-color: var(--primary);
            color: var(--white);
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .btn-group {
                width: 100%;
                flex-direction: column;
            }
            
            .btn {
                width: 35%;
                justify-content: center;
            }
            
            .stat-card {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="header">
        <h1 class="page-title">Dashboard UKM</h1>
        <div class="btn-group">
            <a href="#" class="btn btn-primary">
                <span>➕</span>
                <span>Tambah Event</span>
            </a>
            <a href="../logout.php" class="btn btn-secondary">
                <span>🚪</span>
                <span>Keluar</span>
            </a>
        </div>
    </div>
    
    <div class="isi">
    <div class="stat-card">
        <div class="stat-title">Total Event</div>
        <div class="stat-value"><?= mysqli_num_rows($events) ?></div>
    </div>
    
    <h2 style="margin-bottom: 1rem; color: var(--primary);">Daftar Event Terbaru</h2>
    <div class="events-table">
        <table>
            <thead>
                <tr>
                    <th>Nama Event</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $today = date('Y-m-d');
            $has_event = false;
            while ($e = mysqli_fetch_assoc($events)):
                $has_event = true;
                $event_date = date('Y-m-d', strtotime($e['tanggal']));
                if ($event_date > $today) {
                    $status = 'upcoming';
                    $status_text = 'Mendatang';
                } elseif ($event_date == $today) {
                    $status = 'active';
                    $status_text = 'Aktif';
                } else {
                    $status = 'completed';
                    $status_text = 'Selesai';
                }
            ?>
                <tr>
                    <td><?= htmlspecialchars($e['judul']) ?></td>
                    <td><?= htmlspecialchars($e['tanggal']) ?></td>
                    <td><span class="status <?= $status ?>"><?= $status_text ?></span></td>
                    <td>
                        <a href="edit-event.php?id=<?= $e['id'] ?>" class="action-btn">✏️</a>
                        <a href="proses-event.php?delete=<?= $e['id'] ?>" class="action-btn">🗑️</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if (!$has_event): ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">Tidak ada event</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    </div>
</body>
</html>