const sidebar = document.getElementById("sidebar");
  const dataContainer = document.getElementById("data-container");
  const toggleBtn = document.getElementById("toggle-sidebar-btn");

  let sidebarVisible = true;

  toggleBtn.addEventListener("click", () => {
    sidebarVisible = !sidebarVisible;

    if (sidebarVisible) {
      sidebar.style.display = "block";
      dataContainer.style.marginLeft = "270px";
      toggleBtn.textContent = "✖";
      toggleBtn.style.marginLeft = "0px";
      toggleBtn.title = "Hide Menu Bar";
    } else {
      sidebar.style.display = "none";
      dataContainer.style.marginLeft = "10px";
      toggleBtn.textContent = "☰";
      toggleBtn.style.marginLeft = "-210px";
    }
  });

  document.getElementById('info-button').addEventListener('click', () => {
    const popup = document.getElementById('info-popup');
    popup.style.display = (popup.style.display === 'block') ? 'none' : 'block';
  });

  document.addEventListener('click', (event) => {
    const popup = document.getElementById('info-popup');
    if (!popup.contains(event.target) && event.target.id !== 'info-button') {
      popup.style.display = 'none';
    }
  });

  function highlightButton(selectedBtn) {
    const buttons = document.querySelectorAll('#sensor-list button, #groupDataBtn');
    buttons.forEach(btn => btn.classList.remove('active-sensor'));
    if (selectedBtn) {
      selectedBtn.classList.add('active-sensor');
    }
  }

  function loadSensorData(sensor, btn = null) {
  const group = document.getElementById('group-select').value;

  highlightButton(btn);
    if (!sensor) {
        document.getElementById("data-table").innerHTML = "<p style='color:red;'>Please select a sensor.</p>";
        return;
    }

  fetch(`show_data.php?sensor=${sensor}&group=${group}`)
    .then(response => response.json())
    .then(data => {
      let html = "<table><tr>";
      if (data.length > 0) {
        const keys = Object.keys(data[0]).filter(key => key !== 'group_no');
        keys.forEach(key => html += `<th>${key}</th>`);
        html += "</tr>";

        data.forEach(row => {
          html += "<tr>";
          keys.forEach(key => html += `<td>${row[key]}</td>`);
          html += "</tr>";
        });

      } else {
        html += "<td colspan='100%'>No data found</td></tr>";
      }
      html += "</table>";
      document.getElementById("data-table").innerHTML = html;
    })
    .catch(error => {
      console.error("Error fetching data:", error);
      document.getElementById("data-table").innerHTML = "<p style='color:red;'>Error fetching data.</p>";
    });
}

  function onGroupChange(showGroup = false) {
  
    loadGroupData(); // Show full group
   
  }

  let currentSensor = 'temperature_sensor';

  function loadSensorDataWrapper(sensor) {
    currentSensor = sensor;
    loadSensorData(currentSensor);
  }

  // NEW: Load all data from selected group
  function loadGroupData(btn = null) {
    const group = document.getElementById('group-select').value;

    highlightButton(btn);

    fetch(`show_data.php?group=${group}`)
      .then(response => response.json())
      .then(data => {
        let html = "<table><tr>";
        if (data.length > 0) {
          const keys = Object.keys(data[0]).filter(key => key !== 'group_no');
          keys.forEach(key => html += `<th>${key}</th>`);
          html += "</tr>";

          data.forEach(row => {
            html += "<tr>";
            keys.forEach(key => html += `<td>${row[key]}</td>`);
            html += "</tr>";
          });
        } else {
          html += "<td colspan='100%'>No data found</td></tr>";
        }
        html += "</table>";
        document.getElementById("data-table").innerHTML = html;
      })
      .catch(error => {
        console.error("Error fetching group data:", error);
        document.getElementById("data-table").innerHTML = "<p style='color:red;'>Error fetching group data.</p>";
      });
  }

// showProjectDescription(group);
 function showProjectDescription() {
  const group = document.getElementById('group-select').value;

  if (!group) {
    alert("Please select a group first.");
    return;
  }

  fetch(`get_project_description.php?group=${group}`)
    .then(response => response.json())
    .then(data => {
      const modalContent = document.getElementById('projectContent');

      if (data.error) {
        modalContent.innerHTML = `<p style="color:red;">${data.error}</p>`;
      } else {
        modalContent.innerHTML = `
          <h3>${data.project_title}</h3>
          <p><strong>Group Name:</strong> Group ${data.group_name}</p>
          <p><strong>Group Members:</strong><br> ${data.group_members}</p>
          <p><strong>Problem Statement:</strong><br>${data.problem_statement}</p>
          <p><strong>Sensor Name:</strong> ${data.sensor_name}</p>
          <p><strong>Actuator Name:</strong> ${data.actuator_name}</p>
          
          <p><strong>Project Description:</strong><br>${data.project_description}</p>
        `;
      }

      document.getElementById('projectModal').style.display = 'block';
      document.getElementById('modalBackdrop').style.display = 'block';
    })
    .catch(error => {
      console.error("Error fetching project description:", error);
      document.getElementById('projectContent').innerHTML = `<p style="color:red;">Error loading project description.</p>`;
      document.getElementById('projectModal').style.display = 'block';
      document.getElementById('modalBackdrop').style.display = 'block';
    });
}

function closeModal() {
  document.getElementById('projectModal').style.display = 'none';
  document.getElementById('modalBackdrop').style.display = 'none';
}
