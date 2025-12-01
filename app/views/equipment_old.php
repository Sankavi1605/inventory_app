<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Construction Equipment Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/style.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/equipment.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="logo">
                <a href="<?php echo URLROOT; ?>" class="logo-image">
                    <img class="logo-image" src="<?php echo URLROOT; ?>/public/img/logo.png" alt="Sameepa Logo">
                </a>
                ConstructStock
            </div>
            <nav class="menu">
                <a href="<?php echo URLROOT; ?>/index" class="menu-item"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="<?php echo URLROOT; ?>/inventory/inventory" class="menu-item"><i class="fas fa-box"></i> Inventory</a>
                <a href="<?php echo URLROOT; ?>/inventory/equipment" class="menu-item active"><i class="fas fa-tools"></i> Equipment</a>
            </nav>
        </aside>

        <div class="container">
            <div class="header-main">
                <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                    <div style="display: flex; align-items: center; gap: 2rem;">
                        <h1 style="margin: 0;">Construction Equipment Management</h1>
                        <div class="header-controls">
                            <button class="btn add-new" onclick="openModal()"><i class="fas fa-plus"></i> Add Equipment</button>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['user_id'])) : ?>
                        <div class="user-info" style="display: flex; align-items: center; gap: 1rem; padding: 0.5rem 1rem; background: #e8f4fd; border-radius: 25px;">
                            <i class="fas fa-user-circle" style="color: #007bff; font-size: 1.2rem;"></i>
                            <span style="color: #333; font-weight: 600;">
                                <?php echo htmlspecialchars($_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name']); ?>
                            </span>
                            <a href="<?php echo URLROOT; ?>/auth/logout" class="logout-btn" style="color: #e74c3c; font-weight: 600; text-decoration: none; padding: 0.3rem 0.8rem; background: #ffeaea; border-radius: 15px; transition: all 0.3s;">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    <?php else : ?>
                        <div style="display: flex; gap: 1rem;">
                            <a href="<?php echo URLROOT; ?>/auth/login" class="btn-login" style="color: #007bff; font-weight: 600; text-decoration: none; padding: 0.5rem 1rem; border: 2px solid #007bff; border-radius: 8px; transition: all 0.3s;">Login</a>
                            <a href="<?php echo URLROOT; ?>/auth/signup" class="btn-signup" style="color: #fff; font-weight: 600; text-decoration: none; padding: 0.5rem 1rem; background: #007bff; border-radius: 8px; transition: all 0.3s;">Sign Up</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <main>
                <section class="equipment-section">
                    <div class="equipment-grid">
                        <div class="equipment-card available">
                            <div class="equipment-status">Available</div>
                            <img src="<?php echo URLROOT; ?>/public/img/excavator.jpeg" alt="Excavator">
                            <div class="equipment-details">
                                <h3>CAT 320 Excavator</h3>
                                <div class="specs">
                                    <p><i class="fas fa-weight"></i> Capacity: 20 Tons</p>
                                    <p><i class="fas fa-clock"></i> Hours Used: 1200</p>
                                    <p><i class="fas fa-tools"></i> Maintenance Due: 48hrs</p>
                                </div>
                                <div class="action-buttons">
                                <button class="btn fas fa-edit"></button>
                                <div id="order-status"></div>
                                <div id="equipment-analytics"></div>

                                </div>
                            </div>
                        </div>

                        <div class="equipment-card unavailable">
                            <div class="equipment-status">In Use</div>
                            <img src="<?php echo URLROOT; ?>/public/img/cm.jpeg" alt="Concrete Mixer">
                            <div class="equipment-details">
                                <h3>Industrial Concrete Mixer</h3>
                                <div class="specs">
                                    <p><i class="fas fa-fill"></i> Capacity: 9m³</p>
                                    <p><i class="fas fa-clock"></i> Hours Used: 800</p>
                                    <p><i class="fas fa-calendar"></i> Return: Jan 30, 2024</p>
                                </div>
                                <div class="action-buttons">
                                    <button class="btn unavailable" disabled>In Use</button>
                                    <button class="btn request-return">Request Return</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="equipment-history">
                    <div class="section-header">
                        <h2>Equipment History & Analytics</h2>

                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Equipment</th>
                                <th>Project</th>
                                <th>Check Out</th>
                                <th>Due Date</th>
                                <th>Operator</th>
                                <th>Status</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>CAT 320 Excavator</td>
                                <td>Site A Foundation</td>
                                <td>Jan 3, 2024</td>
                                <td>Jan 5, 2024</td>
                                <td>John Doe</td>
                                <td><span class="status-badge returned">Returned</span></td>

                            </tr>
                            <tr>
                                <td>CAT 320 Excavator</td>
                                <td>Site A Foundation</td>
                                <td>mar 6, 2024</td>
                                <td>mar 15, 2024</td>
                                <td>John Doe</td>
                                <td><span class="status-badge returned">Returned</span></td>

                            </tr>
                        </tbody>
                    </table>
                </section>

                <section class="real-time-tracking">
                    <h2>Real-Time Equipment Monitoring</h2>
                    <div class="tracking-grid" id="tracking-container">
                        <!-- Dynamic content will be inserted here by JavaScript -->
                    </div>
                </section>

                <section class="alert-settings">
                    <div class="section-header">
                        <h2>Alert Settings</h2>
                        <!-- <button class="btn configure"><i class="fas fa-cog"></i> Configure</button> -->
                    </div>
                    <div class="alert-grid">
                        <div class="alert-card">
                            <i class="fas fa-exclamation-triangle"></i>
                            <h3>Low Stock Alert</h3>
                            <p>Threshold: 20% of capacity</p>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="alert-card">
                            <i class="fas fa-clock"></i>
                            <h3>Equipment Due Alert</h3>
                            <p>Notification: 48hrs before deadline</p>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="alert-card">
                            <i class="fas fa-tools"></i>
                            <h3>Maintenance Alert</h3>
                            <p>Schedule: Weekly check required</p>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>


<!-- Modal for adding equipment -->
<div id="add-equipment-modal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal1()">&times;</span>
        <h2>Add Equipment</h2>
        <form id="add-equipment-form">
            <div class="form-group">
                <label for="equipment-name">Equipment Name:</label>
                <input type="text" id="equipment-name" name="equipment-name" required>
            </div>
            <div class="form-group">
                <label for="equipment-capacity">Capacity:</label>
                <input type="text" id="equipment-capacity" name="equipment-capacity" required>
            </div>
            <div class="form-group">
                <label for="equipment-hours">Hours:</label>
                <input type="number" id="equipment-hours" name="equipment-hours" required>
            </div>
            <div class="form-group">
                <label for="equipment-status">Status:</label>
                <select id="equipment-status" name="equipment-status" required>
                    <option value="Available">Available</option>
                    <!-- <option value="In Use">In Use</option>
                    <option value="Under Maintenance">Under Maintenance</option> -->
                </select>
            </div>
            <div class="form-group">
                <label for="equipment-image">Equipment Image:</label>
                <input type="file" id="equipment-image" name="equipment-image" accept="image/*" required>
                <div id="image-preview" class="image-preview"></div>
            </div>
            <button type="submit">Add Equipment</button>
        </form>
    </div>
</div>

</div>


<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal2()">&times;</span>
        <h2>Edit Equipment</h2>
        <form id="editForm">
        <div class="form-group">
            <label for="equipmentName">Equipment Name:</label>
            <input type="text" id="equipmentName" name="equipmentName" required>
            </div>
         <div class="form-group">
            <label for="capacity">Capacity:</label>
            <input type="text" id="capacity" name="capacity" required>
            </div>
            <div class="form-group">
            <label for="hoursUsed">Hours Used:</label>
            <input type="text" id="hoursUsed" name="hoursUsed" required>
            </div>
            <div class="form-group">
            <label for="maintenanceDue">Maintenance Due:</label>
            <input type="text" id="maintenanceDue" name="maintenanceDue" required>
            </div>

            <button type="button" onclick="saveChanges()">Save Changes</button>
            <button type="button" onclick="closeModal2()">Cancel</button>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>

<style>
.btn-login:hover {
  background: #007bff !important;
  color: #fff !important;
  transform: translateY(-1px);
}
.btn-signup:hover {
  background: #0056b3 !important;
  transform: translateY(-1px);
}
.logout-btn:hover {
  background: #e74c3c !important;
  color: #fff !important;
  transform: translateY(-1px);
}
.user-info {
  transition: all 0.3s ease;
}
.user-info:hover {
  background: #d1e9ff !important;
}
</style>
</body>
<script>
      // Global Variables
const trackingData = [
    {
        equipment: "CAT 320 Excavator",
        possessedBy: "John Doe",
        location: "Site A",
        status: "Active",
        hoursToday: 5,
        fuelLevel: 78,
        maintenanceStatus: "Optimal",
        image: "<?php echo URLROOT; ?>/public/img/excavator.jpeg"
    },
    {
        equipment: "Industrial Concrete Mixer",
        possessedBy: "Sarah Smith",
        location: "Site B",
        status: "In Use",
        hoursToday: 6.2,
        fuelLevel: 45,
        maintenanceStatus: "Service Due",
        image: "<?php echo URLROOT; ?>/public/img/cm.jpeg"
    }
];

// Update UI with tracking data
function updateTrackingUI() {
    const trackingContainer = document.getElementById("tracking-container");
    trackingContainer.innerHTML = "";

    trackingData.forEach((item) => {
        const card = document.createElement("div");
        card.className = "tracking-card";
        card.innerHTML = `
            <div class="tracking-header">
                <img src="${item.image}" alt="${item.equipment}">
                <h3>${item.equipment}</h3>
            </div>
            <div class="tracking-info">
                <p><i class="fas fa-user"></i> <strong>Operator:</strong> ${item.possessedBy}</p>
                <p><i class="fas fa-map-marker-alt"></i> <strong>Location:</strong> ${item.location}</p>
                <p><i class="fas fa-clock"></i> <strong>Hours Today:</strong> ${item.hoursToday}hrs</p>
                <p><i class="fas fa-gas-pump"></i> <strong>Fuel Level:</strong> ${item.fuelLevel}%</p>
                <p><i class="fas fa-tools"></i> <strong>Maintenance:</strong> ${item.maintenanceStatus}</p>
            </div>
            <div class="tracking-status ${item.maintenanceStatus === 'Optimal' ? 'status-optimal' : 'status-warning'}">
                ${item.status}
            </div>
        `;
        trackingContainer.appendChild(card);
    });
}

// Update UI every 10 seconds
setInterval(updateTrackingUI, 10000);

// Initial render
updateTrackingUI();


// Function to open the Add Equipment modal
function openModal() {
    document.getElementById("add-equipment-modal").style.display = "block";
}

// Function to close the Add Equipment modal
function closeModal1() {
    document.getElementById("add-equipment-modal").style.display = "none";
}

document.getElementById("add-equipment-form").addEventListener("submit", function(event) {
    event.preventDefault();

    const name = document.getElementById("equipment-name").value;
    const capacity = document.getElementById("equipment-capacity").value;
    const hours = document.getElementById("equipment-hours").value;
    const status = document.getElementById("equipment-status").value;

    // Make sure all inputs are filled before adding the equipment
    if (name && capacity && hours && status) {
        // Create an equipment card dynamically
        const equipmentCard = document.createElement("div");
        equipmentCard.classList.add("equipment-card");

        equipmentCard.innerHTML = `
            <div class="equipment-status">${status}</div>
            <img src="${document.getElementById("image-preview").querySelector("img") ? document.getElementById("image-preview").querySelector("img").src : ''}" alt="${name}">
            <div class="equipment-details">
                <h3>${name}</h3>
                <div class="specs">
                    <p><i class="fas fa-weight"></i> Capacity: ${capacity}</p>
                    <p><i class="fas fa-clock"></i> Hours: ${hours}</p>
                    <p><i class="fas fa-tools"></i> Maintenance Due: N/A</p>
                </div>
                <div class="action-buttons">
                    <button class="btn fas fa-edit"></button>
                    <div id="order-status"></div>
                    <div id="equipment-analytics"></div>
                </div>
            </div>
        `;

        // Add the new equipment card to the equipment section
        document.querySelector(".equipment-grid").appendChild(equipmentCard);

        // Reset the form
        document.getElementById("add-equipment-form").reset();

        // Close the modal
        closeModal1();
    } else {
        alert("Please fill all fields before submitting.");
    }
});


// Show image preview when a file is selected
document.getElementById("equipment-image").addEventListener("change", function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById("image-preview");
    preview.innerHTML = "";
    if (file) {
        const img = document.createElement("img");
        img.src = URL.createObjectURL(file);
        img.style.maxWidth = "100px";
        img.style.maxHeight = "100px";
        preview.appendChild(img);
    }
});
// Add event listeners to each equipment card to open the edit modal
document.querySelectorAll(".equipment-card").forEach(card => {
    card.addEventListener("click", function() {
        openEditModal(card);
    });
});

// Function to open the Edit Equipment modal and populate it with the selected equipment's details
function openEditModal(equipmentCard) {
    // Mark the selected equipment card
    document.querySelectorAll(".equipment-card").forEach(card => card.classList.remove("selected"));
    equipmentCard.classList.add("selected");

    // Get the equipment details from the card
    const name = equipmentCard.querySelector("h3").innerText;
    const capacity = equipmentCard.querySelector(".specs p:nth-child(1)").innerText.replace('Capacity: ', '');
    const hours = equipmentCard.querySelector(".specs p:nth-child(2)").innerText.replace('Hours: ', '');
    const maintenanceDue = equipmentCard.querySelector(".specs p:nth-child(3)") ?
        equipmentCard.querySelector(".specs p:nth-child(3)").innerText.replace('Maintenance Due: ', '') : 'N/A';

    // Populate the modal with the existing values
    document.getElementById("equipmentName").value = name;
    document.getElementById("capacity").value = capacity;
    document.getElementById("hoursUsed").value = hours;
    document.getElementById("maintenanceDue").value = maintenanceDue;

    // Show the Edit modal
    document.getElementById("editModal").style.display = "block";
}

// Function to save the changes made in the Edit modal
function saveChanges() {
    const name = document.getElementById("equipmentName").value;
    const capacity = document.getElementById("capacity").value;
    const hoursUsed = document.getElementById("hoursUsed").value;
    const maintenanceDue = document.getElementById("maintenanceDue").value;

    // Find the selected equipment card
    const equipmentCard = document.querySelector(".equipment-card.selected");

    if (equipmentCard) {
        // Update the equipment details in the card
        equipmentCard.querySelector("h3").innerText = name;
        equipmentCard.querySelector(".specs p:nth-child(1)").innerText = `Capacity: ${capacity}`;
        equipmentCard.querySelector(".specs p:nth-child(2)").innerText = `Hours: ${hoursUsed}`;
        equipmentCard.querySelector(".specs p:nth-child(3)").innerText = `Maintenance Due: ${maintenanceDue}`;

        // Close the modal after saving changes
        closeModal2();
    }
}

// Close modal function
function closeModal2() {
    document.getElementById("editModal").style.display = "none";
}

// Add event listener to save changes button
document.querySelector("#saveChangesButton").addEventListener("click", saveChanges);


// Function to close the Edit Equipment modal
function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}

// Event listener for edit buttons
document.querySelectorAll(".equipment-card .btn.fas.fa-edit").forEach(button => {
    button.addEventListener("click", function() {
        const equipmentCard = this.closest(".equipment-card");
        openEditModal(equipmentCard);
    });
});

// Close the Edit modal when the close button is clicked
document.querySelector(".close").addEventListener("click", closeEditModal);

    </script>
</html>