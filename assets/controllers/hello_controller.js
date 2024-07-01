import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 */
export default class extends Controller {

    static targets = [ "name" ]

        greet() {
            //  const name = this.nameTarget.value
            console.log(`Hello, ${this.name}!`)   
       }
    
       get name() {
        return this.nameTarget.value
      }
}
