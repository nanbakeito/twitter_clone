import './bootstrap';

import { createApp } from 'vue'
import TestVue from './Components/Test.vue';

const app = createApp(TestVue)
app.mount('#app')