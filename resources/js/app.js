import './bootstrap';
import 'preline';
import { HSOverlay, HSStaticMethods } from 'preline';

window.HSOverlay = HSOverlay;
window.HSStaticMethods = HSStaticMethods;

if (typeof window !== 'undefined' && window.HSStaticMethods) {
    window.HSStaticMethods.autoInit();
}
