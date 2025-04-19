////// Paramètres de l'application

const REFRESH_RATE = 60; // en secondes

////// Raccourcis

const btnCoupling      = document.querySelector('#btnCoupling');
const btnDelete        = document.querySelectorAll('.btn-delete');
const btnDeleteConfirm = document.querySelector('.btn-delete-confirm');
const checkId          = document.querySelectorAll('.checkId');
const countDisplay     = document.querySelector('#couplingCount');
const filterGender     = document.querySelector('#filterGender');
const filterRace       = document.querySelector('#filterRace');
const formCoupling     = document.querySelector('#formCoupling');
const formList         = document.querySelector('#formList');
const perPage          = document.querySelector('#perPage');
const modalDelete      = document.getElementById('modalDelete');

////// Fonctions

const autoReproduce = () => {
	fetch('/reproduce', { method: 'PATCH', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
		.then(response => response.text())
		.then(text => {
			try {
				const data = JSON.parse(text);
				if (data.success && data.generated > 0) {
					const message = formatBirthMessage(data.generated);
					
					const alertDiv = document.createElement('div');
					alertDiv.className = 'alert alert-info alert-dismissible fade show';
					alertDiv.role = 'alert';
					alertDiv.innerHTML = `
						<strong>Naissance :</strong> ${message}
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
					`;

					const target = formList || document.body;
					target.parentNode.insertBefore(alertDiv, target);

					setTimeout(() => formList.submit(), 3000);
				}
			} catch(error) {
				console.warn('Réponse non JSON de /reproduce :', text);
			}
		})
		.catch(error => console.log(error));
};

const deleteRow = id => {
	fetch(`/delete/${id}`, { method: 'DELETE', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
		.then(response => {
			if (!response.ok) throw new Error(response.statusText);
		})
		.catch(error => console.log(error));
}

const formatBirthMessage = count => {
	const plural = count > 1;
	return plural 
		? `${count} nouveaux serpents sont nés`
		: `1 nouveau serpent est né`;
};

const updateStatus = () => {
	fetch('/update_status', { method: 'PATCH', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
		.then(response => {
			if (!response.ok) throw new Error(response.statusText);
		})
		.catch(error => console.log(error));
}

////// Observateurs d'évènements et initialisation

if (formList) {

	setInterval(updateStatus,  REFRESH_RATE * 1000);
	setInterval(autoReproduce, REFRESH_RATE * 1000);

	let selectedId = 0;

	if (btnCoupling) {
		btnCoupling.addEventListener('click', function () {
			const checked = Array.from(document.querySelectorAll('.checkId:checked'));
		
			if (checked.length !== 2) {
				alert('Veuillez sélectionner 2 serpents à accoupler.');
				return;
			}
		
			const [checkbox1, checkbox2] = checked;
			const row1 = checkbox1.closest('tr');
			const row2 = checkbox2.closest('tr');
		
			const gender1 = row1.children[6].textContent.trim();
			const gender2 = row2.children[6].textContent.trim();
			const race1   = row1.children[5].textContent.trim();
			const race2   = row2.children[5].textContent.trim();
		
			if (gender1 === gender2) {
				alert('Veuillez sélectionner un mâle et une femelle.');
				return;
			}
		
			if (race1 !== race2) {
				alert('Les deux serpents doivent être de la même race pour s’accoupler.');
				return;
			}
		
			const id1 = checkbox1.value;
			const id2 = checkbox2.value;
		
			const idMale   = gender1 === 'Mâle' ? id1 : id2;
			const idFemale = gender1 === 'Femelle' ? id1 : id2;
		
			formCoupling.querySelector('input[name="id_male"]').value   = idMale;
			formCoupling.querySelector('input[name="id_female"]').value = idFemale;
			formCoupling.submit();
		});		
	}

	if (btnDelete) {
		btnDelete.forEach(el => {
			el.addEventListener('click', function (e) {
				const button = e.target.closest('.btn-delete');
				if (!button) return;

				selectedId = button.dataset['id'] || 0;
			});
		});
	}

	if (btnDeleteConfirm) {
		btnDeleteConfirm.addEventListener('click', function () {
			if (selectedId) {
				deleteRow(selectedId);
				formList.submit();

				const modal = bootstrap.Modal.getInstance(modalDelete);
				if (modal) {
					modal.hide();
				}
			}
		});
	}

	if (checkId) {
		checkId.forEach(el => {
			el.addEventListener('change', function () {
				const countChecked = Array.from(checkId).filter(c => c.checked).length;
				if (countDisplay) {
					countDisplay.textContent = `Sélectionnés : ${countChecked} / 2`;
				}
			});
			el.addEventListener('click', function (e) {
				const countChecked = Array.from(checkId).filter(checkbox => checkbox.checked).length;
				if (countChecked > 2) {
					e.preventDefault();
					this.checked = false;
					alert('Vous ne pouvez sélectionner que 2 serpents maximum.');
				}
			});
		});
	}

	if (filterGender && filterRace) {
		[filterGender, filterRace].forEach(el => {
			el.addEventListener('change', function () {
				formList.submit();
			});
		});
	}

	perPage.addEventListener('change', function () {
		formList.submit();
	});
}

updateStatus();
