importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyDa-Mt_Z2_e49PCYE-vjGnw3nkJJ86nYFQ",
    projectId: "laravel-25776",
    messagingSenderId: "433141213721",
    appId: "1:433141213721:web:6285804aadb32becfed888"
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});
