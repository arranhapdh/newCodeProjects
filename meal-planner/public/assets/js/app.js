// Personal calendar meal modal
function openMealModal(day, slot) {
    const modal = document.getElementById('mealModal');
    document.getElementById('modal-day').value = day;
    document.getElementById('modal-slot').value = slot;

    // Set default times
    const timeInput = modal.querySelector('input[name="meal_time"]');
    if (slot === 'breakfast') timeInput.value = '08:00';
    else if (slot === 'lunch') timeInput.value = '12:00';
    else if (slot === 'dinner') timeInput.value = '17:30';

    modal.showModal();
}

function closeMealModal() {
    document.getElementById('mealModal').close();
}

// Household meal modal
function openHouseholdMealModal(day, slot) {
    const modal = document.getElementById('householdMealModal');
    document.getElementById('hh-modal-day').value = day;
    document.getElementById('hh-modal-slot').value = slot;

    const timeInput = modal.querySelector('input[name="meal_time"]');
    if (slot === 'breakfast') timeInput.value = '08:00';
    else if (slot === 'lunch') timeInput.value = '12:00';
    else if (slot === 'dinner') timeInput.value = '17:30';

    modal.showModal();
}

function closeHouseholdMealModal() {
    document.getElementById('householdMealModal').close();
}

// AI button loading spinners
document.addEventListener('DOMContentLoaded', function () {
    const aiButtons = document.querySelectorAll('#btn-suggest, #btn-modify');
    aiButtons.forEach(function (btn) {
        btn.closest('form').addEventListener('submit', function () {
            btn.setAttribute('aria-busy', 'true');
            btn.textContent = 'Thinking...';
        });
    });
});
