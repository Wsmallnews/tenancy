import axios from 'axios';

import Swiper from 'swiper/bundle';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Swiper = Swiper;