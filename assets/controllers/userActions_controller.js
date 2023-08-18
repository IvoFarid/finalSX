import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['qfollowers', 'qfollowing', 'bfollow', 'username', 'photo', 'editbtn', 'sbutton', 'userdata']
  static values = {qfollowers: Number, qfollowing: Number, userlogged: Number, userprofile: Number, username: String, usermail: String, imagepath: String}

  sendRequest(idprofile, typeAction) {
    const url = `/users/${idprofile}/${typeAction}`; // Replace this with your actual Symfony endpoint URL
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

  setFollow(event){
    event.preventDefault();
    if (event.params.action === "follow") {
      this.qfollowersValue=this.qfollowersValue+1;
      this.qfollowersTarget.outerHTML=`<p data-userActions-target="qfollowers" class="text-4xl font-bold text-zinc-700">${this.qfollowersValue}</p>`;
      this.bfollowTarget.outerHTML=`<button class="text-red-700 select-none rounded-lg px-1 border border-red-700 hover:bg-red-700 hover:text-slate-50 transition" data-action="click->userActions#setFollow" data-userActions-action-param="unfollow" data-userActions-target="bfollow">Unfollow</button>`
      this.sendRequest(this.userprofileValue, event.params.action)
    } else {
      this.qfollowersValue=this.qfollowersValue-1;
      this.qfollowersTarget.outerHTML=`<p data-userActions-target="qfollowers" class="text-4xl font-bold text-zinc-700">${this.qfollowersValue}</p>`;
      this.bfollowTarget.outerHTML=`<button class="text-blue-700 select-none rounded-lg px-1 border border-blue-700 hover:bg-blue-700 hover:text-slate-50 transition" data-action="click->userActions#setFollow" data-userActions-action-param="follow" data-userActions-target="bfollow">Follow</button>`
      this.sendRequest(this.userprofileValue, event.params.action)
    }
  }

  // updateUser(name){
  //   const url = `/users/edit`; // Replace this with your actual Symfony endpoint URL
  //   const options = {
  //     method: "POST",
  //     body: name // Change to "GET" or "PUT" or "DELETE" if needed
  //   };
  //   return fetch(url, options)
  //     .then(response => response.json())
  //     .then(data => console.log(data, 'LA DATA RETORNADA'))
  //     .catch(error => {
  //       console.error("Error fetching data:", error);
  //       throw error; // Rethrow the error for handling in the calling function
  //     });
  // }

  showEditMenu(event){
    if (event.params.action==="editTrue") {
      console.log(this.imagepathValue)
      console.log(this.usernameTarget.innerText);
      // this.userdataTarget.classList.add('hidden')
      this.userdataTarget.outerHTML=
     `<form method="POST" data-turbo="false" action="${this.userloggedValue}/edit" class="flex gap-3 p-3 w-2/4 max-w-[50%]" data-userActions-target="userdata" enctype="multipart/form-data">
        <label data-userActions-target="photo" for="inputfile" class="flex items-center justify-center w-[60px] h-[60px] min-h-[60px] min-w-[60px] rounded-full" style="cursor:pointer">
          <input id="inputfile" type="file" class="hidden" name="image">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.5 6C1.5 4.75736 2.50736 3.75 3.75 3.75H20.25C21.4926 3.75 22.5 4.75736 22.5 6V18C22.5 19.2426 21.4926 20.25 20.25 20.25H3.75C2.50736 20.25 1.5 19.2426 1.5 18V6ZM3 16.0607V18C3 18.4142 3.33579 18.75 3.75 18.75H20.25C20.6642 18.75 21 18.4142 21 18V16.0607L18.3107 13.3713C17.7249 12.7855 16.7751 12.7855 16.1893 13.3713L15.3107 14.25L16.2803 15.2197C16.5732 15.5126 16.5732 15.9874 16.2803 16.2803C15.9874 16.5732 15.5126 16.5732 15.2197 16.2803L10.0607 11.1213C9.47487 10.5355 8.52513 10.5355 7.93934 11.1213L3 16.0607ZM13.125 8.25C13.125 7.62868 13.6287 7.125 14.25 7.125C14.8713 7.125 15.375 7.62868 15.375 8.25C15.375 8.87132 14.8713 9.375 14.25 9.375C13.6287 9.375 13.125 8.87132 13.125 8.25Z" fill="#0F172A"/>
          </svg>
        </label>
        <div class="flex flex-col">
          <div class="flex gap-1 items-center">
            <p class="text-xs text-slate-400 select-none">${this.usermailValue}</p>
            <div class="text-xs font-semibold rounded-lg bg-transparent text-zinc-800 px-1 hover:bg-zinc-800 hover:text-slate-50 border border-zinc-800 transition">
              <button data-action="click->userActions#showEditMenu" data-userActions-action-param="editFalse" data-userActions-target="editbtn">Back</button>
            </div>
            <div class="text-xs font-semibold rounded-lg px-1 hover:bg-blue-500 transition hover:text-slate-50">
              <button type="submit">Update</button>
            </div>
          </div>
          <div class="flex items-center">
            <input data-userActions-target="username" style="font-size:2.25rem; max-width:200px; min-width:100px; line-height:2.5rem" class="max-w-min font-bold outline-none bg-transparent" type="text" placeholder="${this.usernameTarget.innerText}" name="username"> 
          </div>
        </div>
        </form>`
    } else if (event.params.action==="editFalse") {
      this.userdataTarget.outerHTML=
     `<div class="flex gap-3 p-3 w-2/4" data-userActions-target="userdata">
        <div class="w-[60px] h-[60px]">
          <img src="${this.imagepathValue}" alt="userPhoto" class="w-full h-full rounded-full" data-userActions-target="photo">
        </div>
        <div class="flex flex-col">
          <div class="flex gap-1 items-center">
            <p class="text-xs text-slate-400 select-none">${this.usermailValue}</p>
            <div class="text-xs font-semibold rounded-lg bg-transparent text-zinc-800 px-1 hover:bg-zinc-800 hover:text-slate-50 border border-zinc-800 transition"><button data-action="click->userActions#showEditMenu" data-userActions-action-param="editTrue" data-userActions-target="editbtn">Edit Profile</button></div>
          </div>
          <div class="flex items-center">
            <p data-userActions-target="username" class="text-4xl font-bold text-zinc-700 select-none">${this.usernameValue}</p>
          </div>
        </div>
      </div>`
    }  
  }
}