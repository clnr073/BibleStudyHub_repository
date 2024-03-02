import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/*global Image*/

import PhotoSwipeLightbox from 'photoswipe/lightbox';
import 'photoswipe/style.css';

window.addEventListener('load', () => {
  const gallery = document.querySelector('#photo-gallery');
  if (!gallery) return; // #my-galleryが存在しない場合は何もしない

  const photoswipe = new PhotoSwipeLightbox({
    gallery: '#photo-gallery',
    children: 'a',
    showHideAnimationType: 'fade',
    pswpModule: () => import('photoswipe')
  });
  photoswipe.init();

  gallery.querySelectorAll('a').forEach(elem => {
    const img = new Image();
    img.src = elem.href;
    img.onload = () => {
      elem.dataset.pswpWidth = img.width;
      elem.dataset.pswpHeight = img.height;
    };
  });
});
