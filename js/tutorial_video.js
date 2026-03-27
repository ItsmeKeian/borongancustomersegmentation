const tutorialBtn = document.getElementById("openTutorialBtn");
const tutorialVideo = document.getElementById("tutorialVideo");
const tutorialModal = new bootstrap.Modal(document.getElementById("tutorialModal"));

// CHANGE THIS TO YOUR YOUTUBE EMBED LINK
const videoURL = "https://www.youtube.com/embed/_Z-oh_dI15w?si=4Lnj3vTu2l1-2hW6"; 

tutorialBtn.addEventListener("click", () => {
    tutorialVideo.src = videoURL;
    tutorialModal.show();
});

// Stop video when closing modal
document.getElementById("tutorialModal").addEventListener("hidden.bs.modal", () => {
    tutorialVideo.src = "";
});