<script setup lang="ts">
import Lucide from "@/components/Base/Lucide";

interface ScrollToTopProps {
  visible: boolean,
}

const props = withDefaults(defineProps<ScrollToTopProps>(), {
  visible: true,
});

const goTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

<template>
  <div v-if="props.visible"
    class="fixed bottom-0 right-0 z-50 flex items-center justify-center w-auto h-auto mb-5 mr-20 p-2 border rounded-full shadow-md cursor-pointer box"
    @click="goTop">
    <Lucide icon="ArrowUp" class="w-5 h-5 hover:animate-bounce" />
  </div>
</template>
