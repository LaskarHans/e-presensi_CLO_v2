// Firebase siap digunakan di seluruh frontend Laravel kamu!

import './bootstrap.js';
import { auth, db } from './firebase-config';

window.firebaseAuth = auth;
window.firebaseDb = db;
