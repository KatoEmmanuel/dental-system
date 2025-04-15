document.getElementById('dob').addEventListener('change', calculateAge);

function calculateAge() {
    const dob = new Date(document.getElementById('dob').value);
    const today = new Date();
    let age = today.getFullYear() - dob.getFullYear();
    const monthDiff = today.getMonth() - dob.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
        age--;
    }
    
    document.getElementById('age').value = age + ' years';
}

function updateOutput(selectId, outputId) {
    const select = document.getElementById(selectId);
    const output = document.getElementById(outputId);
    const selectedOptions = Array.from(select.selectedOptions).map(option => option.text);
    output.value = selectedOptions.join(', ');
}

document.getElementById('deciduous-select').addEventListener('change', function() {
    updateOutput('deciduous-select', 'deciduous-output');
});

document.getElementById('adults-select').addEventListener('change', function() {
    updateOutput('adults-select', 'adults-output');
});

let timeout = null;

function autoSearch() {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        document.getElementById('searchForm').submit();
    }, 500); // Adjust the delay as needed
}

function showPatientDetails(patient) {
    const details = `
        <p><strong>ID:</strong> ${patient.id}</p>
        <p><strong>Name:</strong> ${patient.name}</p>
        <p><strong>Date of Birth:</strong> ${patient.dob}</p>
        <p><strong>Age:</strong> ${patient.age}</p>
        <p><strong>Gender:</strong> ${patient.gender}</p>
        <p><strong>Occupation:</strong> ${patient.occupation}</p>
        <p><strong>Medical History:</strong> ${patient.medical_history}</p>
        <p><strong>Phone:</strong> ${patient.phone}</p>
        <p><strong>Parafunctional Behavior:</strong> ${patient.parafunctional_behavior}</p>
        <p><strong>Chief Complaint 1:</strong> ${patient.chief_complaint_1}</p>
        <p><strong>Chief Complaint 2:</strong> ${patient.chief_complaint_2}</p>
        <p><strong>Chief Complaint 3:</strong> ${patient.chief_complaint_3}</p>
        <p><strong>Chief Complaint 4:</strong> ${patient.chief_complaint_4}</p>
        <p><strong>Pre Treatment:</strong> ${patient.pre_treatment}</p>
        <p><strong>During RX:</strong> ${patient.during_rx}</p>
        <p><strong>Post RX:</strong> ${patient.post_rx}</p>
        <p><strong>Skeletal Features:</strong> ${patient.skeletal_features}</p>
        <p><strong>Vertical Overlap:</strong> ${patient.vertical_overlap_class}</p>
        <p><strong>Lower Crowding:</strong> ${patient.lower_crowding}</p>
        <p><strong>Upper Crowding:</strong> ${patient.upper_crowding}</p>
        <p><strong>Face Profile:</strong> ${patient.face_profile}</p>
        <p><strong>Dental Features:</strong> ${patient.dental_features}</p>
        <p><strong>Contact of Incisors:</strong> ${patient.contact_of_incisors}</p>
        <p><strong>Max Mand Relation:</strong> ${patient.max_mand_relation}</p>
        <p><strong>Etiology:</strong> ${patient.etiology}</p>
        <p><strong>Deciduous Dentition:</strong> ${patient.deciduous_dentition}</p>
        <p><strong>Adults:</strong> ${patient.adults}</p>
    `;
    document.getElementById('patientDetails').innerHTML = details;
    $('#patientModal').modal('show');
}

