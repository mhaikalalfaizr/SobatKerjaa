<header>
    <h1>SobatKerja</h1>
    <nav>
        <ul>
            <li><a href="vacancy/list.php">Lowongan</a></li>
            <?php if (isset($_SESSION['user_id'])) : ?>
                <?php if ($_SESSION['user_type'] === 'JobSeeker') : ?>
                    <li><a href="../jobseeker/dashboard.php">Dashboard</a></li>
                <?php elseif ($_SESSION['user_type'] === 'UMKM') : ?>
                    <li><a href="../umkm/dashboard.php">Dashboard</a></li>
                <?php endif; ?>
                <li><a href="auth/logout.php">Logout</a></li>
            <?php else : ?>
                <li><a href="auth/login.php">Login</a></li>
                <li><a href="auth/register_type.php">Daftar</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>