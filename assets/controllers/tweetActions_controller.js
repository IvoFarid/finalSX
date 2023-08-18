import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = [ "qlikes", "qretweets", "qsaved", "bsave", "blike", "bretweet" ];
  static values = { qlikes: Number, qretweets: Number, qsaved: Number, tweetid: Number};

  sendRequest(id, typeAction) {
    const url = `/tweets/${id}/${typeAction}`; // Replace this with your actual Symfony endpoint URL
    const options = {
      method: "POST" // Change to "GET" or "PUT" or "DELETE" if needed
    };
    return fetch(url, options)
      .then(response => response.json())
      .then(data => console.log(data, 'LA DATA RETORNADA'))
      .catch(error => {
        console.error("Error fetching data:", error);
        throw error; // Rethrow the error for handling in the calling function
      });
  }

  setLike(event) {
    event.preventDefault();
    if (event.params.action === "like") {
      try {
        this.qlikesTarget.outerHTML= `<p class="text-xs" data-tweetActions-qlikes-value=${this.qlikesValue+1} data-tweetActions-target="qlikes">${this.qlikesValue+1}</p>`;
        this.blikeTarget.outerHTML= '<button data-action="click->tweetActions#setLike" data-tweetActions-action-param="dislike" data-tweetActions-target="blike" class="hover:bg-red-500 transition rounded-full p-1"> <svg width="16" height="16" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"> <path d="M11.645 20.9107L11.6384 20.9072L11.6158 20.8949C11.5965 20.8844 11.5689 20.8693 11.5336 20.8496C11.4629 20.8101 11.3612 20.7524 11.233 20.6769C10.9765 20.5261 10.6132 20.3039 10.1785 20.015C9.31074 19.4381 8.15122 18.5901 6.9886 17.5063C4.68781 15.3615 2.25 12.1751 2.25 8.25C2.25 5.32194 4.7136 3 7.6875 3C9.43638 3 11.0023 3.79909 12 5.0516C12.9977 3.79909 14.5636 3 16.3125 3C19.2864 3 21.75 5.32194 21.75 8.25C21.75 12.1751 19.3122 15.3615 17.0114 17.5063C15.8488 18.5901 14.6893 19.4381 13.8215 20.015C13.3868 20.3039 13.0235 20.5261 12.767 20.6769C12.6388 20.7524 12.5371 20.8101 12.4664 20.8496C12.4311 20.8693 12.4035 20.8844 12.3842 20.8949L12.3616 20.9072L12.355 20.9107L12.3523 20.9121C12.1323 21.0289 11.8677 21.0289 11.6477 20.9121L11.645 20.9107Z" fill="#ef233c" stroke="#ef233c" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/> </svg> </button>'
        this.sendRequest(this.tweetidValue, event.params.action);
        this.qlikesValue=this.qlikesValue+1;
      } 
      catch (error) {
        console.log(error)
      }

    } else if(event.params.action === "dislike") {
      try {
        this.qlikesTarget.outerHTML= `<p class="text-xs" data-tweetActions-qlikes-value=${this.qlikesValue-1} data-tweetActions-target="qlikes">${this.qlikesValue-1}</p>`;
        this.blikeTarget.outerHTML= '<button data-action="click->tweetActions#setLike" data-tweetActions-action-param="like" data-tweetActions-target="blike" class="hover:bg-red-500 transition rounded-full p-1"> <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M11.645 20.9107L11.6384 20.9072L11.6158 20.8949C11.5965 20.8844 11.5689 20.8693 11.5336 20.8496C11.4629 20.8101 11.3612 20.7524 11.233 20.6769C10.9765 20.5261 10.6132 20.3039 10.1785 20.015C9.31074 19.4381 8.15122 18.5901 6.9886 17.5063C4.68781 15.3615 2.25 12.1751 2.25 8.25C2.25 5.32194 4.7136 3 7.6875 3C9.43638 3 11.0023 3.79909 12 5.0516C12.9977 3.79909 14.5636 3 16.3125 3C19.2864 3 21.75 5.32194 21.75 8.25C21.75 12.1751 19.3122 15.3615 17.0114 17.5063C15.8488 18.5901 14.6893 19.4381 13.8215 20.015C13.3868 20.3039 13.0235 20.5261 12.767 20.6769C12.6388 20.7524 12.5371 20.8101 12.4664 20.8496C12.4311 20.8693 12.4035 20.8844 12.3842 20.8949L12.3616 20.9072L12.355 20.9107L12.3523 20.9121C12.1323 21.0289 11.8677 21.0289 11.6477 20.9121L11.645 20.9107Z" stroke="#0F172A" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/> </svg> </button>'
        this.sendRequest(this.tweetidValue, event.params.action);
        this.qlikesValue=this.qlikesValue-1;
      }
      catch (error) {
        console.log(error)
      }
    }
  }

  setRetweet(event) {
    event.preventDefault();
    let rtwManipulation = this.qretweetsValue
    if (event.params.action === "addRtw") {
      try {
        rtwManipulation++;
        this.qretweetsTarget.outerHTML= `<p class="text-xs" data-tweetActions-qretweets-value=${rtwManipulation} data-tweetActions-target="qretweets" data-tweetActions-addRetweet-class="bg-green-400">${rtwManipulation}</p>`;
        this.bretweetTarget.outerHTML= ' <button data-action="click->tweetActions#setRetweet" data-tweetActions-action-param="deleteRtw" data-tweetActions-target="bretweet" class="hover:bg-blue-500 transition rounded-full p-1"> <svg width="16" height="16" viewBox="0 0 24 24" fill="#023e8a22" xmlns="http://www.w3.org/2000/svg"> <path d="M19.5 12C19.5 10.7681 19.4536 9.54699 19.3624 8.3384C19.2128 6.35425 17.6458 4.78724 15.6616 4.63757C14.453 4.54641 13.2319 4.5 12 4.5C10.7681 4.5 9.54699 4.54641 8.3384 4.63757C6.35425 4.78724 4.78724 6.35425 4.63757 8.3384C4.62097 8.55852 4.60585 8.77906 4.59222 9M19.5 12L22.5 9M19.5 12L16.5 9M4.5 12C4.5 13.2319 4.54641 14.453 4.63757 15.6616C4.78724 17.6458 6.35425 19.2128 8.3384 19.3624C9.54699 19.4536 10.7681 19.5 12 19.5C13.2319 19.5 14.453 19.4536 15.6616 19.3624C17.6458 19.2128 19.2128 17.6458 19.3624 15.6616C19.379 15.4415 19.3941 15.2209 19.4078 15M4.5 12L7.5 15M4.5 12L1.5 15" stroke="#023e8a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/> </svg> </button>'
        this.sendRequest(this.tweetidValue, event.params.action);
      } catch (error) {
        console.log(error)
      }
    } else if(event.params.action === "deleteRtw") {
      try {
        // because i do not store the likes, if i delete the like i should reset with the initial value that i got
        this.qretweetsTarget.outerHTML= `<p class="text-xs" data-tweetActions-qretweets-value=${rtwManipulation} data-tweetActions-target="qretweets" data-tweetActions-addRetweet-class="bg-green-400">${rtwManipulation}</p>`;
        this.bretweetTarget.outerHTML= '<button data-action="click->tweetActions#setRetweet" data-tweetActions-action-param="addRtw" data-tweetActions-target="bretweet" class="hover:bg-blue-500 transition rounded-full p-1"> <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M19.5 12C19.5 10.7681 19.4536 9.54699 19.3624 8.3384C19.2128 6.35425 17.6458 4.78724 15.6616 4.63757C14.453 4.54641 13.2319 4.5 12 4.5C10.7681 4.5 9.54699 4.54641 8.3384 4.63757C6.35425 4.78724 4.78724 6.35425 4.63757 8.3384C4.62097 8.55852 4.60585 8.77906 4.59222 9M19.5 12L22.5 9M19.5 12L16.5 9M4.5 12C4.5 13.2319 4.54641 14.453 4.63757 15.6616C4.78724 17.6458 6.35425 19.2128 8.3384 19.3624C9.54699 19.4536 10.7681 19.5 12 19.5C13.2319 19.5 14.453 19.4536 15.6616 19.3624C17.6458 19.2128 19.2128 17.6458 19.3624 15.6616C19.379 15.4415 19.3941 15.2209 19.4078 15M4.5 12L7.5 15M4.5 12L1.5 15" stroke="#0F172A" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/> </svg>'
        this.sendRequest(this.tweetidValue, event.params.action);
      } catch (error) {
        console.log(error)
      }
    }
  }

  setSaved(event) {
    console.log(this.tweetidValue, event.params.action);
    event.preventDefault();
    if (event.params.action === "save") {
      try {
        this.qsavedValue = this.qsavedValue+1;
        this.qsavedTarget.outerHTML= `<p class="text-xs" data-tweetActions-target="qsaved">${this.qsavedValue}</p>`;
        this.bsaveTarget.outerHTML= '<button data-action="click->tweetActions#setSaved" data-tweetActions-action-param="deleteSave" data-tweetActions-target="bsave" class="hover:bg-zinc-500 transition rounded-full p-1"> <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <path fill-rule="evenodd" clip-rule="evenodd" d="M6.32022 2.57741C8.18374 2.36114 10.079 2.25 12 2.25C13.921 2.25 15.8163 2.36114 17.6798 2.57741C19.1772 2.75119 20.25 4.03722 20.25 5.50699V21C20.25 21.2599 20.1154 21.5013 19.8943 21.638C19.6732 21.7746 19.3971 21.7871 19.1646 21.6708L12 18.0885L4.83541 21.6708C4.60292 21.7871 4.32681 21.7746 4.1057 21.638C3.88459 21.5013 3.75 21.2599 3.75 21V5.50699C3.75 4.03722 4.82283 2.75119 6.32022 2.57741Z" fill="#0F172A"/> </svg> </button>'
        
        this.sendRequest(this.tweetidValue, event.params.action);
      } catch (error) {
        console.log(error)
      }
    } else if(event.params.action === "deleteSave") {
      try {
        this.qsavedTarget.outerHTML= `<p class="text-xs" data-tweetActions-target="qsaved">${this.qsavedValue-1}</p>`;
        this.bsaveTarget.outerHTML= '<button data-action="click->tweetActions#setSaved" data-tweetActions-action-param="save" data-tweetActions-target="bsave" class="hover:bg-zinc-500 transition rounded-full p-1"> <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M17.5933 3.32241C18.6939 3.45014 19.5 4.399 19.5 5.50699V21L12 17.25L4.5 21V5.50699C4.5 4.399 5.30608 3.45014 6.40668 3.32241C8.24156 3.10947 10.108 3 12 3C13.892 3 15.7584 3.10947 17.5933 3.32241Z" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg> </button>'
        this.qsavedValue = this.qsavedValue-1;
        this.sendRequest(this.tweetidValue, event.params.action);
      } catch (error) {
        console.log(error)
      }
    }
  }
  
  addComment(e) {
    e.preventDefault();
    const form = event.target.form;
    console.log(e)
  }

  // async setClicked(event){
  //   event.preventDefault();
  //   console.log('clickee el tweet');
  // }
}