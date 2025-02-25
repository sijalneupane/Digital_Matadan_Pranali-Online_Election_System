function setMinDOB() {
  // const loader = document.getElementById("loading"); // Reference the loader
  // loader.style.display = "block"; // Show the loader

  try {
      let response =  fetch("https://timeapi.io/api/Time/current/zone?timeZone=UTC");
      let data =response.json();

      let currentDate = new Date(data.dateTime);
      currentDate.setFullYear(currentDate.getFullYear() - 18);

      document.getElementById("dateOfBirth").setAttribute("max", currentDate.toISOString().split("T")[0]);
  } catch (error) {
      console.error("Failed to fetch time from API. Falling back to local time.");

      // Fallback to local time
      let today = new Date();
      today.setFullYear(today.getFullYear() - 18);
      let formattedDate = today.toISOString().split("T")[0];

      document.getElementById("dateOfBirth").setAttribute("max", formattedDate);
  } finally {
      loader.style.display = "none"; // Hide the loader
      
  }
}

document.addEventListener("DOMContentLoaded", setMinDOB);