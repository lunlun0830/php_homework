<?php
require 'db.php';

if (isset($_POST['add'])) {
    $email = trim($_POST['email'] ?? '');
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = mysqli_prepare($conn, 'INSERT IGNORE INTO email_list (email) VALUES (?)');
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
    }
    header('Location: index.php');
    exit;
}

$result = mysqli_query($conn, 'SELECT No, email FROM email_list ORDER BY No');
$emails = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>郵件寄送系統</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #eef2f6; color: #263238; font-family: Arial, "Microsoft JhengHei", sans-serif; }
        main { width: min(900px, 92%); margin: 35px auto; }
        h1 { margin-bottom: 20px; text-align: center; }
        .box { margin-bottom: 18px; padding: 22px; border-radius: 8px; background: white; box-shadow: 0 3px 12px #ccd3da; }
        h2 { margin-top: 0; font-size: 20px; }
        input, textarea, select, button { width: 100%; margin: 6px 0 12px; padding: 10px; border: 1px solid #bbc4cc; border-radius: 5px; font: inherit; }
        button { border: 0; background: #1976d2; color: white; font-weight: bold; cursor: pointer; }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 9px; border-bottom: 1px solid #ddd; text-align: left; }
        .progress { height: 22px; overflow: hidden; border-radius: 5px; background: #ddd; }
        #bar { width: 0; height: 100%; background: #43a047; transition: width .2s; }
        #status { font-weight: bold; text-align: center; }
        small { color: #68747d; }
        @media (max-width: 600px) { .row { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<main>
    <h1>郵件寄送系統</h1>

    <section class="box">
        <h2>Email 資料庫</h2>
        <form method="post">
            <input type="email" name="email" placeholder="輸入 Email" required>
            <button name="add">新增 Email</button>
        </form>
        <table>
            <tr><th>No</th><th>Email</th></tr>
            <?php foreach ($emails as $item): ?>
                <tr>
                    <td><?= (int) $item['No'] ?></td>
                    <td><?= htmlspecialchars($item['email']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <section class="box">
        <h2>基本郵件介面</h2>
        <form id="mailForm">
            <input id="subject" placeholder="郵件主旨" required>
            <textarea id="content" rows="5" placeholder="郵件內容" required></textarea>

            <div class="row">
                <label>寄送方式
                    <select id="mode">
                        <option value="all">全部寄送</option>
                        <option value="random">隨機寄送幾筆</option>
                    </select>
                </label>
                <label>隨機筆數
                    <input id="amount" type="number" min="1" value="1">
                </label>
            </div>

            <div class="row">
                <label>間隔秒數
                    <input id="seconds" type="number" min="0" max="10" value="1">
                </label>
                <label>時間模式
                    <select id="timeMode">
                        <option value="fixed">固定間隔</option>
                        <option value="random">隨機間隔</option>
                    </select>
                </label>
            </div>

            <button type="submit">開始寄送</button>
            <small>課堂展示版：模擬寄送進度，不會真的大量寄信。</small>
        </form>
    </section>

    <section class="box">
        <h2>寄送進度</h2>
        <div class="progress"><div id="bar"></div></div>
        <p id="status">0 / 0</p>
    </section>
</main>

<script>
const emailCount = <?= count($emails) ?>;
const form = document.querySelector('#mailForm');
const bar = document.querySelector('#bar');
const statusText = document.querySelector('#status');

form.addEventListener('submit', async (event) => {
    event.preventDefault();

    let total = emailCount;
    if (document.querySelector('#mode').value === 'random') {
        total = Math.min(emailCount, Number(document.querySelector('#amount').value));
    }
    if (total < 1) {
        alert('請先新增 Email');
        return;
    }

    const seconds = Number(document.querySelector('#seconds').value);
    const randomTime = document.querySelector('#timeMode').value === 'random';

    for (let sent = 1; sent <= total; sent++) {
        if (sent > 1) {
            const waitSeconds = randomTime ? Math.floor(Math.random() * (seconds + 1)) : seconds;
            await new Promise(resolve => setTimeout(resolve, waitSeconds * 1000));
        }
        statusText.textContent = `${sent} / ${total}`;
        bar.style.width = `${sent / total * 100}%`;
    }

    statusText.textContent += '　寄送完成';
});
</script>
</body>
</html>

