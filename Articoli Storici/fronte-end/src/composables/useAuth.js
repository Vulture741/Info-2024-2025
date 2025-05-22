import { ref } from 'vue';

const user = ref(JSON.parse(sessionStorage.getItem('user')) || null);
const searchQuery = ref('');

function loginUser(u) {
  sessionStorage.setItem('user', JSON.stringify(u));
  user.value = u;
}

function logoutUser() {
  sessionStorage.clear();
  user.value = null;
  searchQuery.value = '';
}

export function useAuth() {
  return {
    user,
    loginUser,
    logoutUser,
    searchQuery
  };
}
