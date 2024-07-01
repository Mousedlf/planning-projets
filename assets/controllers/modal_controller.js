import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['modal'];

    close() {
    this.modalTarget.classList.add("hidden");
  //  console.log('feeeeeeeeeeermeeuh')
    };


    open() {
        modal.classList.remove("hidden");
    };

}