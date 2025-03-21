document.getElementById('toggle-option-1').addEventListener('click', function() {
    document.getElementById('selected-option').value = '1';
    document.getElementById('option-1').classList.remove('hidden');
    document.getElementById('option-2').classList.add('hidden');
    this.classList.add('bg-blue-500', 'text-white');
    this.classList.remove('bg-gray-300', 'text-black');
    document.getElementById('toggle-option-2').classList.add('bg-gray-300', 'text-black');
    document.getElementById('toggle-option-2').classList.remove('bg-blue-500', 'text-white');
});

document.getElementById('toggle-option-2').addEventListener('click', function() {
    document.getElementById('selected-option').value = '2';
    document.getElementById('option-2').classList.remove('hidden');
    document.getElementById('option-1').classList.add('hidden');
    this.classList.add('bg-blue-500', 'text-white');
    this.classList.remove('bg-gray-300', 'text-black');
    document.getElementById('toggle-option-1').classList.add('bg-gray-300', 'text-black');
    document.getElementById('toggle-option-1').classList.remove('bg-blue-500', 'text-white');
});