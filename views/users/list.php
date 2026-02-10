<style>
.users-list-container {
    max-width: 1200px;
    margin: 20px auto;
    font-family: Arial, sans-serif;
}

.admin-header {
    font-size: 24px;
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

.user-row.header {
    display: flex;
    background-color: #4CAF50 !important; 
    color: white !important;
    font-weight: bold;
    padding: 10px 5px;
    border-radius: 6px 6px 0 0;
}

.user-row {
    display: flex;
    border: 1px solid #ddd;
    border-top: none;
    padding: 10px 5px;
    align-items: center;
    gap: 10px;
    background-color: #fff;
    transition: background-color 0.2s;
}

.user-row:nth-child(even) {
    background-color: #f9f9f9;
}

.user-row:hover {
    background-color: #f1f1f1;
}

.user-cell {
    flex: 1 1 150px;
    word-wrap: break-word;
    display: flex;      
    align-items: center;    
    justify-content: center; 
    text-align: center;  
}

.user-cell.name { flex-basis: 150px; }
.user-cell.email { flex-basis: 200px; }
.user-cell.phone { flex-basis: 120px; }
.user-cell.address { flex-basis: 200px; }
.user-cell.role { flex-basis: 100px; }
.user-cell.actions { flex-basis: 180px; }

.user-actions {
    display: flex;           
    flex-direction: column;  
    gap: 10px;               
    align-items: center;    
}

.user-actions a {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 14px;
    text-decoration: none;
    color: white;
    transition: background-color 0.2s;
}

.user-actions a.edit {
    background-color: #4CAF50; 
}

.user-actions a.edit:hover {
    background-color: #326b34;
}

.user-actions a.delete {
    background-color: #ef4444;
}

.user-actions a.delete:hover {
    background-color: #b91c1c;
}
</style>

<div class="users-list-container">
    <h1 class="admin-header">Список користувачів</h1>

    <div class="user-row header">
        <div class="user-cell name">Ім'я</div>
        <div class="user-cell email">Email</div>
        <div class="user-cell phone">Телефон</div>
        <div class="user-cell address">Адреса</div>
        <div class="user-cell role">Роль</div>
        <div class="user-cell actions">Дії</div>
    </div>

    <?php foreach ($users as $user): ?>
        <div class="user-row">
            <div class="user-cell name"><?= htmlspecialchars($user['name']) ?></div>
            <div class="user-cell email"><?= htmlspecialchars($user['email']) ?></div>
            <div class="user-cell phone"><?= htmlspecialchars($user['phone']) ?></div>
            <div class="user-cell address"><?= htmlspecialchars($user['address']) ?></div>
            <div class="user-cell role"><?= htmlspecialchars($user['role']) ?></div>
            <div class="user-cell actions user-actions">
                <a class="edit" href="index.php?controller=adminUsers&action=edit&id=<?= $user['userId'] ?>">Редагувати</a>
                <a class="delete" href="index.php?controller=adminUsers&action=delete&id=<?= $user['userId'] ?>"
                   onclick="return confirm('Ви впевнені, що хочете видалити цього користувача?');">
                   Видалити
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
