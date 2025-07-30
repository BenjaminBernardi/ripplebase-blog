import {LinkedItem} from "./linked-item";

class UpdateForm {

    private linkedElements: Map<string, LinkedItem> = new Map();

    constructor(btn: string, input: string) {
        const btnUpdates: NodeListOf<HTMLButtonElement> = document.querySelectorAll(btn);
        btnUpdates.forEach((btn) => {
            const id: string = btn.dataset.btnUpdate;
            this.linkedElements.set(id, {
                btn: btn,
                // '[data-text-update="{id}"]'
                paragraph: document.querySelector('[' + input +  '="' + id + '"]') as HTMLParagraphElement
            });
        });

        this.linkedElements.forEach((linkedItem, key) => {
            linkedItem.btn.addEventListener('click', () => {
                // Masquer le bouton modifier
                linkedItem.btn.classList.add('d-none');
                // Masquer le paragraph
                linkedItem.paragraph.classList.add('d-none');

                // Vérifier si ma map contient le textArea lié à cette combinaison de bouton/paragraph
                if (!linkedItem.textArea) {
                    const textArea: HTMLTextAreaElement = document.createElement('textarea');
                    textArea.value = linkedItem.paragraph.innerText;
                    textArea.classList.add('w-100');
                    textArea.style.height = '150px';
                    linkedItem.textArea = textArea;
                }
                // Ajoute le textarea après le paragraph
                linkedItem.paragraph.after(linkedItem.textArea);

                // Si oui, je l'affiche
                // Sinon, je la créé et je l'affiche

                // Création d'un bouton "Valider"
                if (!linkedItem.btnValidate) {
                    const btnValidate: HTMLButtonElement = document.createElement('button');
                    btnValidate.classList.add('btn', 'btn-success', 'mx-2');
                    btnValidate.innerText = 'Valider';
                    linkedItem.btnValidate = btnValidate;
                }
                // On ajoute le bouton "Valider" dans le DOM, avant le bouton "modifier"
                linkedItem.btn.before(linkedItem.btnValidate);

                // Ajout de l'action click sur le bouton "Valider"
                linkedItem.btnValidate.addEventListener('click', () => {
                    const data: string = JSON.stringify({'description': linkedItem.textArea.value});
                    fetch('/edit-comment/' + key, {method: 'POST', body: data})
                        .then((response) => {
                            if (response.status === 200) {
                                return response.json();
                            }
                        })
                        .then((json) => {
                            if (json) {
                                linkedItem.paragraph.innerText = linkedItem.textArea.value;
                                this.hideForm(linkedItem);
                            } else {
                                window.location.href = json;
                            }
                        });
                });

                // Création d'un bouton "Annuler"
                if (!linkedItem.btnCancel) {
                    const btnCancel: HTMLButtonElement = document.createElement('button');
                    btnCancel.classList.add('btn', 'btn-danger', 'mx-2');
                    btnCancel.innerText = 'Annuler';
                    // On l'ajoute dans la map pour le conserver
                    linkedItem.btnCancel = btnCancel;
                }

                // On ajoute le bouton "Annuler" dans le DOM, avant le bouton "modifier"
                linkedItem.btn.before(linkedItem.btnCancel);

                // Ajout de l'action click sur le bouton "Annuler"
                linkedItem.btnCancel.addEventListener('click', () => {
                    this.hideForm(linkedItem);
                });
            });
        })
    }

    hideForm(linkedItem: LinkedItem): void {
        linkedItem.btnCancel.remove();
        linkedItem.btnValidate.remove();
        linkedItem.textArea.remove();
        linkedItem.btn.classList.remove('d-none');
        linkedItem.paragraph.classList.remove('d-none');
    }

}


window.addEventListener('load', () => {
   new UpdateForm('[data-btn-update]', 'data-text-update');
});
