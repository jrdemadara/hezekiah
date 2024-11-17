<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { useClipboard } from '@vueuse/core';
import {} from '@inertiajs/vue3';
import { Network, QrCode, UsersRound } from 'lucide-vue-next';
import * as d3 from 'd3';
const { props } = usePage();

const source = ref('');
source.value = props.auth.user.code;

const { text, copy, copied, isSupported } = useClipboard({ source });

const aapl = [
    // Example data structure (replace this with your actual data)
    { date: new Date(2007, 3, 23), close: 93.24 },
    { date: new Date(2007, 3, 24), close: 98.35 },
    { date: new Date(2007, 3, 25), close: 93.84 },
    { date: new Date(2007, 3, 26), close: 102.92 },
    { date: new Date(2007, 3, 29), close: 98.8 },
    { date: new Date(2007, 4, 1), close: 99.47 },
    { date: new Date(2007, 4, 2), close: 97.39 },
    { date: new Date(2007, 4, 3), close: 108.4 },
    { date: new Date(2007, 4, 4), close: 99.81 },
    { date: new Date(2007, 4, 7), close: 97.92 },
    { date: new Date(2007, 4, 8), close: 95.06 },
    { date: new Date(2007, 4, 9), close: 94.88 },
    { date: new Date(2007, 4, 9), close: 92.34 },
    { date: new Date(2007, 4, 10), close: 95.74 },
    { date: new Date(2007, 4, 13), close: 100.36 },
    { date: new Date(2007, 4, 14), close: 107.52 },
    { date: new Date(2007, 4, 15), close: 107.34 },
    { date: new Date(2007, 4, 16), close: 109.44 },
    { date: new Date(2007, 4, 17), close: 110.02 },
    { date: new Date(2007, 4, 20), close: 111.98 },

    // Add more data points...
];

const chart = ref(null);

onMounted(() => {
    // Declare chart dimensions and margins.
    const width = 928;
    const height = 500;
    const marginTop = 20;
    const marginRight = 30;
    const marginBottom = 30;
    const marginLeft = 40;

    // Declare the x (horizontal position) scale.
    const x = d3.scaleUtc(
        d3.extent(aapl, (d) => d.date),
        [marginLeft, width - marginRight],
    );

    // Declare the y (vertical position) scale.
    const y = d3.scaleLinear(
        [0, d3.max(aapl, (d) => d.close)],
        [height - marginBottom, marginTop],
    );

    // Declare the line generator.
    const line = d3
        .line()
        .x((d) => x(d.date))
        .y((d) => y(d.close));

    // Create the SVG container.
    const svg = d3
        .create('svg')
        .attr('width', width)
        .attr('height', height)
        .attr('viewBox', [0, 0, width, height])
        .attr('style', 'max-width: 100%; height: auto; height: intrinsic;');

    // Add the x-axis.
    svg.append('g')
        .attr('transform', `translate(0,${height - marginBottom})`)
        .call(
            d3
                .axisBottom(x)
                .ticks(width / 80)
                .tickSizeOuter(0),
        );

    // Add the y-axis, remove the domain line, add grid lines, and a label.
    svg.append('g')
        .attr('transform', `translate(${marginLeft},0)`)
        .call(d3.axisLeft(y).ticks(height / 40))
        .call((g) => g.select('.domain').remove())
        .call((g) =>
            g
                .selectAll('.tick line')
                .clone()
                .attr('x2', width - marginLeft - marginRight)
                .attr('stroke-opacity', 0.1),
        )
        .call((g) =>
            g
                .append('text')
                .attr('x', -marginLeft)
                .attr('y', 10)
                .attr('fill', 'currentColor')
                .attr('text-anchor', 'start')
                .text('↑ Daily close ($)'),
        );

    // Append a path for the line.
    svg.append('path')
        .attr('fill', 'none')
        .attr('stroke', 'steelblue')
        .attr('stroke-width', 1.5)
        .attr('d', line(aapl));

    // Attach the SVG element to the DOM inside the `#chart` div
    chart.value.appendChild(svg.node());
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <img
                        :src="props.auth.user.avatar"
                        alt="avatar"
                        class="w-14 rounded-full"
                    />
                    <div class="flex flex-col">
                        <h4 class="text-lg font-semibold capitalize">
                            {{ props.auth.user.firstname }}
                            {{ props.auth.user.lastname }}
                        </h4>
                        <p
                            @click="copy(source)"
                            class="text-sm font-bold uppercase text-[#215439] active:underline"
                        >
                            {{ props.auth.user.code }}
                            <span v-if="!copied">
                                <Copy class="mr-0.5 text-slate-800" :size="16"
                            /></span>
                            <span v-else>
                                <Check class="mr-0.5 text-green-600" :size="16"
                            /></span>
                        </p>
                    </div>
                </div>
                <QrCode :size="28" class="text-" />
            </div>
        </template>

        <div class="flex flex-col bg-white px-4">
            <div class="mt-2 flex flex-col">
                <h6 class="text-gray-400">Total Points</h6>
                <h2 class="text-5xl font-semibold tracking-wider">₱9,700.50</h2>
            </div>
            <div class="mb-4 mt-5 grid w-full grid-cols-2 gap-2 sm:grid-cols-2">
                <div
                    class="flex h-32 flex-col justify-between rounded-3xl bg-[#AFEC70] p-4"
                >
                    <div class="flex items-center justify-between">
                        <h4
                            class="text-lg font-semibold tracking-wide text-[#215439]"
                        >
                            Referrals
                        </h4>
                        <div class="rounded-full bg-green-900 p-3">
                            <UsersRound :size="20" class="text-[#AFEC70]" />
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-xs text-lime-700">Invite Success</p>
                        <h2 class="text-2xl font-semibold text-lime-900">
                            107
                        </h2>
                    </div>
                </div>
                <div
                    class="flex h-32 flex-col justify-between rounded-3xl bg-black p-4"
                >
                    <div class="flex items-center justify-between">
                        <h4
                            class="text-lg font-semibold tracking-wide text-white"
                        >
                            Downlines
                        </h4>
                        <div class="rounded-full bg-[#AFEC70] p-3">
                            <Network :size="20" class="text-black" />
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-xs text-gray-500">Build Networks</p>
                        <h2 class="text-2xl font-semibold text-[#AFEC70]">
                            1,358
                        </h2>
                    </div>
                </div>
            </div>
            <div class="mt-4 h-full w-full" id="chart" ref="chart"></div>
        </div>
    </AuthenticatedLayout>
</template>
