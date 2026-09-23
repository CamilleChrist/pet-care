const speciesSelect = document.querySelector('#species');
const breedSelect = document.querySelector('#breed_id');

if (speciesSelect && breedSelect) {
    const filterBreeds = (resetBreed = false) => {
        const species = speciesSelect.value;

        if (resetBreed) {
            breedSelect.value = '';
        }

        for (const option of breedSelect.options) {
            if (!option.value) {
                continue;
            }

            option.hidden = option.dataset.species !== species;
        }
    };

    // Quand l'utilisateur change d'espèce, on réinitialise la race.
    speciesSelect.addEventListener('change', () => {
        filterBreeds(true);
    });

    // Initialisation : conserve la race en mode édition.
    filterBreeds();
}
