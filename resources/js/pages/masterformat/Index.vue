<script setup lang="ts">
import { ref, computed, shallowRef, useTemplateRef } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { useFuse } from '@vueuse/integrations/useFuse';
import { UseFuseOptions } from '@vueuse/integrations';

interface DataItem {
    division: string
    section: string
}

const props = defineProps({
    divisions: Object,
    sections: Object,
    subsections: Object,
})

const div = ref(null)
const section = ref(null)
const sections = computed(() => {
    return props.divisions.find(item => item.id == div.value?.id)?.csi_section
})
const subsections = computed(() => {
    return section.value?.csi_subsection
})

const data = ref([])
props.divisions.forEach(item => {
    let object = {}
    object.id = item.id
    object.name = item.name
    object.code = item.code
    object.category = 'division'
    object.csi_section = item.csi_section
    data.value.push(object)
})
props.sections.forEach(item => {
    let object = {}
    object.name = item.name
    object.code = item.code
    object.category = 'section'
    object.division = item.csi_division
    data.value.push(object)
})

const keys = computed(() => {
    return ['name']
})

const options = computed<UseFuseOptions<DataItem>>(() => ({
    fuseOptions: {
        keys: keys.value
    }
}))

const search = shallowRef('')
const focus = ref(false)
const { results } = useFuse(search, data, options);
const secSection = useTemplateRef('sectionSection')

const selectDivision = (division) => {
    section.value = null
    div.value = division;
    section.value = div.value.csi_section[0];
    secSection.value.scrollTop = 0
}

const selectSearch = async (searchedItem) => {
    if(searchedItem.item.category === 'division') {
        selectDivision(searchedItem.item)
        document.querySelector('#division-' + div.value.code).scrollIntoView()
    } else if(searchedItem.item.category === 'section') {
        section.value = props.sections.find(section => section.name == searchedItem.item.name)
        div.value = section.value.csi_division
        await
        document.querySelector('#division-' + div.value.code).scrollIntoView()
        document.querySelector('#section-' + section.value.code).scrollIntoView()
    }
    search.value = ''
    focus.value = !focus.value
}

</script>

<template>
    <Head title="Masterformat/CSI codes" />

    <GuestLayout title="Masterformat (CSI) Reference">
        <div class="antialiased bg-gray-50 dark:bg-gray-900" v-motion-fade>
            <main class="p-4 h-auto py-20 md:px-10">
                <div class="select-all">
                    <v-text-field v-model="search" density="compact" hide-details variant="outlined" prepend-inner-icon="mdi-magnify" placeholder="Search divisions and sections..." class="select-all"></v-text-field>
                </div>
                <div class="relative ms-9 ">
                    <div class="absolute bg-light-secondary w-full overflow-auto max-h-80 border-x-2 border-b-2 border-black z-10" ref="resultSection" v-motion-fade v-if="results.length > 0">
                        <div v-for="(result, index) in results" :key="index" class="py-0.5 px-4 cursor-pointer hover:bg-light-tertiary hover:italic hover:font-bold hover:text-md w-full" @click.prevent="selectSearch(result)" :class="{'font-bold text-sm': result.item.category == 'division'}">
                            {{ result.item.division?.code }} {{ result.item.code }} - {{ result.item.name }} - {{ result.item.category }}
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-8">
                    <div>
                        <h1 class="text-lg uppercase">Division</h1>
                        <div class="bg-light-tertiary border-2 border-gray-500 rounded-md dark:border-gray-600 h-96 overflow-auto px-4 py-1" ref="divisionSection">
                            <div v-for="(division, index) in props.divisions" :key="index" class="cursor-pointer" :id="`division-${division.code}`">
                                <div class="p-1 rounded-sm" @click="selectDivision(division)" :class="{'bg-light-primary': div?.id == division.id}">
                                    {{ division.code }} - {{ division.name }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-lg uppercase">Section</h1>
                        <div class="bg-light-tertiary border-2 rounded-md border-gray-500 dark:border-gray-600 h-96 overflow-auto px-4 py-1" ref="sectionSection">
                            <div v-for="(ref_section, i) in sections" :key="i" class="cursor-pointer" :id="`section-${ref_section.code}`">
                                <div class="p-1 rounded-sm" @click="section = ref_section" :class="{'bg-light-primary': section?.id == ref_section.id}">
                                    {{ div.code }} {{ ref_section.code }} - {{ ref_section.name }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-lg uppercase">Subsection</h1>
                        <div class="bg-light-tertiary border-2 rounded-md border-gray-500 dark:border-gray-600 h-96 overflow-auto px-4 py-1">
                            <div class="p-1 rounded-sm" v-for="(ref_subsection, subsectionIndex) in subsections" :key="subsectionIndex">
                                {{ div?.code }} {{ section?.code }} {{ ref_subsection?.code }} - {{ ref_subsection?.name }}
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>



        <!-- <div class="mb-6" v-motion :initial="{ opacity: 0, y:100}" :visibleOnce="{opacity: 1, y:0}"> -->
        <!-- <div class="container mx-auto" v-motion :initial="{opacity: 0, y:100}" :visibleOnce="{opacity: 1, y: 0}"> -->

    </GuestLayout>

</template>
