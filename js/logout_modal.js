// Script for login confirm modal and unique key creation

// Show confirmation modal
function openLogoutModal() {
    document.getElementById("logoutModal").style.display = "flex";
};

// Close modal
function closeLogoutModal() {
    document.getElementById("logoutModal").style.display = "none";
}
// Handle confirmation and redirect
function confirmLogout(userType) {
    var uniqueKey = 'key_' + Math.random().toString(36).substring(2, 12) + '_' + new Date().getMilliseconds();
    if (userType === 'admin') {
        window.location.href = '../admin/admin_logout.php?logout_key=' + uniqueKey;
    }else if (userType === 'voter') {
        window.location.href = '../register_and_login/voter_logout.php?logout_key=' + uniqueKey;
    }
}