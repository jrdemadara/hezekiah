<script setup>
import { ref, onMounted, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';
import { MoveLeft } from 'lucide-vue-next';
import * as d3 from 'd3';

const { props } = usePage();

const data = JSON.parse(props.data); // Parse the JSON string into an object

// Create a reference for the chart container
const chartRef = ref(null);

const createChart = (data) => {
    const width = 928;

    // Compute the tree height; this approach will allow the height of the
    // SVG to scale according to the breadth (width) of the tree layout.
    const root = d3.hierarchy(data);
    const dx = 10;
    const dy = width / (root.height + 1);

    // Create a tree layout.
    const tree = d3.tree().nodeSize([dx, dy]);

    // Sort the tree and apply the layout.
    root.sort((a, b) => d3.ascending(a.data.name, b.data.name));
    tree(root);

    // Compute the extent of the tree. Note that x and y are swapped here
    // because in the tree layout, x is the breadth, but when displayed, the
    // tree extends right rather than down.
    let x0 = Infinity;
    let x1 = -x0;
    root.each((d) => {
        if (d.x > x1) x1 = d.x;
        if (d.x < x0) x0 = d.x;
    });

    // Compute the adjusted height of the tree.
    const height = x1 - x0 + dx * 2;

    const svg = d3
        .create('svg')
        .attr('width', width)
        .attr('height', height)
        .attr('viewBox', [-dy / 3, x0 - dx, width, height])
        .attr('style', 'max-width: 100%; height: auto; font: 10px sans-serif;');

    const link = svg
        .append('g')
        .attr('fill', 'none')
        .attr('stroke', '#555')
        .attr('stroke-opacity', 0.4)
        .attr('stroke-width', 1.5)
        .selectAll()
        .data(root.links())
        .join('path')
        .attr(
            'd',
            d3
                .linkHorizontal()
                .x((d) => d.y)
                .y((d) => d.x),
        );

    const node = svg
        .append('g')
        .attr('stroke-linejoin', 'round')
        .attr('stroke-width', 3)
        .selectAll()
        .data(root.descendants())
        .join('g')
        .attr('transform', (d) => `translate(${d.y},${d.x})`);

    node.append('circle')
        .attr('fill', (d) => (d.children ? '#555' : '#999'))
        .attr('r', 2.5);

    node.append('text')
        .attr('dy', '0.31em')
        .attr('x', (d) => (d.children ? -6 : 6))
        .attr('text-anchor', (d) => (d.children ? 'end' : 'start'))
        .text((d) => d.data.name)
        .attr('stroke', 'white')
        .attr('paint-order', 'stroke');

    // Render the SVG to the container
    const container = chartRef.value;
    container.innerHTML = ''; // Clear any existing content
    container.appendChild(svg.node());
};

// Lifecycle hook to create the chart when the component is mounted
onMounted(() => {
    createChart(data); // Call createChart with initial data
});

// Watch for changes in the data prop
watch(
    () => props.data,
    (newData) => {
        createChart(newData); // Recreate the chart when data changes
    },
);
</script>

<template>
    <Head title="Network" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex">
                <Link :href="route('dashboard')">
                    <MoveLeft />
                </Link>

                <h2
                    class="w-full text-center text-xl font-semibold leading-tight text-gray-800 "
                >
                    Network
                </h2>
            </div>
        </template>

        <div
            class="mx-auto w-full max-w-screen-lg overflow-scroll px-4 sm:px-6 lg:px-8"
        >
            <div ref="chartRef" class="relative overflow-scroll">
                <!-- Chart content will be appended here -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>
