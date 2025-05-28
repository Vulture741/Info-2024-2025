import { ref } from 'vue';

export const user = ref(JSON.parse(sessionStorage.getItem('user')) || null);
export const searchQuery = ref('');

export function logout() {
  fetch('/api/auth.php?action=logout', {
    method: 'POST',
    credentials: 'include'
  });
  user.value = null;
  sessionStorage.removeItem('user');
}
