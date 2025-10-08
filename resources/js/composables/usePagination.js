import { ref } from 'vue';
import { useOffsetPagination } from '@vueuse/core'
import { current } from 'tailwindcss/colors';

export function usePagination(stuff) {
    function fetch(page, pageSize) {
      const start = (page - 1) * pageSize
      const end = start + pageSize
      return stuff.slice(start, end)
    }
    
    return { fetch }
}