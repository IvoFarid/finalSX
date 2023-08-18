import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    static targets = [ "counter" ];
    static classes = [ "suma", "resta" ];

    count=0;

    connect() {
      console.log('startee el controller de mi <p>')
    }
    
    add(event){ 
      event.preventDefault();
      console.log('ABLERTO')
      console.log(this.element)
      this.count++;
      this.element.classList.remove(this.restaClass, 'bg-violet-400');
      this.element.classList.add(this.sumaClass)
      this.counterTarget.textContent=this.count;
    }

    rest(event){ 
      event.preventDefault();
      console.log('ABLERTO')
      console.log(this.element)
      this.element.classList.remove(this.sumaClass, 'bg-violet-400')
      this.element.classList.add(this.restaClass)
      this.count--;
      this.counterTarget.textContent=this.count;
    }

    retweet(event){
      event.preventDefault();
      console.log(event.params)
    }

    like(event){
      event.preventDefault();
      console.log(event.params)
    }
}
