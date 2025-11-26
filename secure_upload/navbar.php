
<style>

.navbar {
    width: 100%;
    padding: 15px 25px;
    background: #0a0a0a;
    border-bottom: 2px solid red;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: Poppins, sans-serif;
    box-shadow: 0 0 15px rgba(255, 0, 0, 0.4);
    position: relative;
}


.navbar .brand {
    font-size: 20px;
    font-weight: bold;
    color: #ff1a1a;
}


.nav-links {
    display: flex;
    gap: 25px;
    position: absolute;
    left: 50%;
    transform: translateX(-50%); 
}


.navbar a {
    color: #ff2e2e;
    text-decoration: none;
    font-weight: bold;
    font-size: 17px;
    transition: 0.3s;
}

.navbar a:hover {
    text-shadow: 0 0 10px red;
}


.hamburger {
    display: none;
    flex-direction: column;
    cursor: pointer;
    gap: 4px;
}

.hamburger div {
    width: 25px;
    height: 3px;
    background-color: #ff2e2e;
    transition: 0.3s;
}

/* Responsive styles */
@media screen and (max-width: 768px) {
    .nav-links {
        display: none; 
        position: absolute;
        top: 60px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #0a0a0a;
        flex-direction: column;
        width: 200px;
        padding: 10px;
        border: 1px solid red;
        border-radius: 5px;
    }

    .nav-links.show {
        display: flex; 
    }

    .hamburger {
        display: flex;
    }
}
</style>

<div class="navbar">
    <div class="brand">Secure Upload System</div>

    <div class="nav-links">
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
        <a href="upload.php">Upload</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="hamburger" onclick="toggleMenu()">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>

<script>
function toggleMenu() {
    const navLinks = document.querySelector('.nav-links');
    navLinks.classList.toggle('show');
}
</script>
