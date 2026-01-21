// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/12.8.0/firebase-app.js";
import { getAnalytics } from "https://www.gstatic.com/firebasejs/12.8.0/firebase-analytics.js";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
    apiKey: "AIzaSyDq8x0Gb-1lBAVBmPZzfCOYmF6gqB1NstA",
    authDomain: "lifeline-notification.firebaseapp.com",
    projectId: "lifeline-notification",
    storageBucket: "lifeline-notification.firebasestorage.app",
    messagingSenderId: "1080725502217",
    appId: "1:1080725502217:web:ee84c49d6dc3c745d6fbd8",
    measurementId: "G-HV7P4TF0G3"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);