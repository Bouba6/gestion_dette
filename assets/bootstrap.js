import './styles/app.css'; // Assurez-vous que le chemin est correct

// Importez Stimulus et votre contrôleur
import { Application } from '@hotwired/stimulus';
import { startStimulusApp } from '@symfony/stimulus-bridge';

// Importez vos contrôleurs
const application = Application.start();
startStimulusApp(require.context('./controllers', true, /\.js$/), application);
