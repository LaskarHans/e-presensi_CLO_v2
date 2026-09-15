// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
import { getAuth } from "firebase/auth";
import { getFirestore } from "firebase/firestore";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyCog7jueEeuC0OqLk7paNJRXc7hGDz7CLg",
  authDomain: "e-presence-class-63d25.firebaseapp.com",
  projectId: "e-presence-class-63d25",
  storageBucket: "e-presence-class-63d25.firebasestorage.app",
  messagingSenderId: "424066258473",
  appId: "1:424066258473:web:b1ad778cd4e4f984eeee38",
  measurementId: "G-0Y3XGLCY9D"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);

export const auth = getAuth(app);
export const db = getFirestore(app);
