const profileIcon = document.getElementById("profile-icon");
const profileOverlay = document.getElementById("profile-container-overlay");

profileIcon.addEventListener("click", () => {
    const isVisible = profileOverlay.style.display === "block";
    profileOverlay.style.display = isVisible ? "none" : "block";
});

document.addEventListener("click", (event) => {
    if (!profileIcon.contains(event.target) && !profileOverlay.contains(event.target)) {
        profileOverlay.style.display = "none";
    }
});


