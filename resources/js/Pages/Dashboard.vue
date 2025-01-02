<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';
import { useClipboard } from '@vueuse/core';
import {} from '@inertiajs/vue3';
import avatar from '../../assets/images/avatar.jpg';

import {
    Check,
    Coins,
    Copy,
    Network,
    QrCode,
    SquareStack,
    UserPlus2,
    Waypoints,
} from 'lucide-vue-next';
import * as d3 from 'd3';
const { props } = usePage();
const code = props.auth.user.code;
const source = ref('');
source.value = code;

const { copy, copied } = useClipboard({ source });

const sam = [
    // Example data structure (replace this with your actual data)
    { date: new Date(2024, 1), close: 0 },
    { date: new Date(2024, 2), close: 14.34 },
    { date: new Date(2024, 3), close: 17.76 },
    { date: new Date(2024, 4), close: 8.56 },
    { date: new Date(2024, 5), close: 11.34 },
    { date: new Date(2024, 6), close: 7.45 },
    { date: new Date(2024, 7), close: 14.67 },
];

const aapl = props.auth.referral_trend; // Get referral trend data
const chart = ref(null);

console.log(aapl);
onMounted(() => {
    // Declare chart dimensions and margins.
    const width = 928;
    const height = 500;
    const marginTop = 20;
    const marginRight = 30;
    const marginBottom = 30;
    const marginLeft = 40;
    console.log(aapl);
    // Parse the date and ensure it's a Date object if not already
    aapl.forEach((d) => {
        // If the date is a string in 'YYYY-M' format, parse it as a Date
        d.date = new Date(`${d.date}-01`); // Convert 'YYYY-M' to 'YYYY-M-01'
        d.close = +d.close; // Ensure 'close' is a number
    });

    // Declare the x (horizontal position) scale.
    const x = d3.scaleUtc(
        d3.extent(aapl, (d) => d.date), // Set x-axis domain based on the min/max of dates
        [marginLeft, width - marginRight], // Position it with margins
    );

    // Declare the y (vertical position) scale.
    const y = d3.scaleLinear(
        [0, d3.max(aapl, (d) => d.close)], // Set y-axis domain based on max of referral count
        [height - marginBottom, marginTop], // Position it with margins
    );

    // Declare the line generator.
    const line = d3
        .line()
        .curve(d3.curveMonotoneX) // Makes the line smooth
        .x((d) => x(d.date))
        .y((d) => y(d.close));

    // Define the area generator (matching the curve)
    const area = d3
        .area()
        .curve(d3.curveMonotoneX) // Ensure the area matches the line curve
        .x((d) => x(d.date))
        .y1((d) => y(d.close))
        .y0(height - marginBottom); // Baseline of the area

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
                .text('↑ Referral Trend'),
        );

    // Add vertical grid lines
    svg.append('g')
        .attr('class', 'grid')
        .attr('transform', `translate(0,${height - marginBottom})`) // Align grid with x-axis position
        .call(
            d3
                .axisBottom(x) // Use the x scale
                .ticks(width / 80) // Adjust tick count for grid spacing
                .tickSize(-(height - marginTop - marginBottom)) // Extend grid lines top-to-bottom
                .tickFormat(''), // Remove labels from grid lines
        )
        .call((g) => g.selectAll('.domain').remove()) // Remove the axis line
        .call((g) => g.selectAll('.tick line').attr('stroke-opacity', 0.1)); // Style grid lines

    // Append the area (background color under the line)
    svg.append('path')
        .attr('fill', '#AFEC70') // Choose your background color
        .attr('fill-opacity', 0.2)
        .attr('d', area(aapl)); // Use the area generator with your data

    // Append a path for the line.
    svg.append('path')
        .attr('fill', 'none')
        .attr('stroke', '#5DA414')
        .attr('stroke-width', 4)
        .attr('d', line(aapl));

    svg.selectAll('circle')
        .data(aapl) // Bind data
        .join('circle') // Enter pattern for circles
        .attr('cx', (d) => x(d.date)) // Position circles horizontally
        .attr('cy', (d) => y(d.close)) // Position circles vertically
        .attr('r', 14) // Set radius to 14 for the circles
        .attr('fill', '#5DA414') // Circle color
        .attr('stroke', 'white') // Optional: Add a border
        .attr('stroke-width', 0.5); // Optional: Thickness of the border

    // Attach the SVG element to the DOM inside the `#chart` div
    chart.value.appendChild(svg.node());
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between font-mono">
                <div class="flex items-center space-x-2">
                    <img
                        :src="props.auth.user.avatar || avatar"
                        alt="avatar"
                        class="w-14 rounded-full"
                    />
                    <div class="flex flex-col">
                        <h4 class="text-lg font-semibold capitalize">
                            {{ props.auth.user.firstname }}
                            {{ props.auth.user.lastname }}
                        </h4>

                        <div
                            @click="copy(source)"
                            class="flex cursor-pointer items-center space-x-1 font-bold uppercase text-[#458500] active:underline"
                        >
                            <span> {{ code }}</span>

                            <span v-if="!copied">
                                <Copy class="mr-0.5" :size="16"
                            /></span>
                            <span v-else>
                                <Check class="mr-0.5" :size="16"
                            /></span>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <Link
                        :href="route('qrcode')"
                        class="rounded-xl bg-[#AFEC70]/10 p-2 ring-1 ring-[#5DA414]/20"
                    >
                        <QrCode :size="24" class="text-[#215439]" />
                    </Link>
                    <!-- <Link
                        :href="route('profile.index')"
                        class="rounded-xl bg-[#AFEC70]/10 p-2 ring-1 ring-[#5DA414]/20"
                    >
                        <UserCog2 :size="24" class="text-[#215439]" />
                    </Link> -->
                </div>
            </div>
        </template>

        <div class="flex flex-col bg-white px-4 font-mono">
            <div class="flex">
                <div class="flex flex-col">
                    <h6 class="text-gray-500">Total Balance</h6>
                    <h2 class="text-5xl font-semibold tracking-wider">
                        ₱{{
                            parseFloat(props.auth.user.referral_points || 0) +
                            parseFloat(props.auth.user.order_points || 0)
                        }}
                    </h2>
                </div>
            </div>

            <div
                class="mt-5 flex w-full items-center justify-between rounded-2xl bg-[#FFCB14] px-6 py-3"
            >
                <div class="flex flex-col text-gray-800">
                    <small class="text-xs">Referral Bonus</small>
                    <big class="font-semibold"
                        >+{{ props.auth.user.referral_points }}</big
                    >
                </div>
                <div class="h-8 w-px bg-gray-400"></div>
                <div class="flex flex-col text-gray-800">
                    <small class="text-xs">Uni-Level Bonus</small>
                    <big class="text-end font-semibold"
                        >+{{ props.auth.user.order_points }}</big
                    >
                </div>
            </div>
            <div class="mb-4 mt-2 grid w-full grid-cols-2 gap-2 sm:grid-cols-2">
                <div
                    class="flex h-32 flex-col justify-between rounded-3xl bg-[#AFEC70] p-4"
                >
                    <div class="flex items-center justify-between">
                        <h4
                            class="text-lg font-semibold tracking-wide text-[#215439]"
                        >
                            Direct
                        </h4>

                        <div class="rounded-full bg-green-900 p-3">
                            <Network :size="20" class="text-[#AFEC70]" />
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-xs text-lime-700">Direct Invites</p>
                        <h2 class="text-2xl font-semibold text-lime-900">
                            {{ props.auth.referrals
                            }}<span class="text-xs font-normal">/Invites</span>
                        </h2>
                    </div>
                </div>
                <div
                    class="flex h-32 flex-col justify-between rounded-3xl bg-[#16151A] p-4"
                >
                    <div class="flex items-center justify-between">
                        <h4
                            class="text-lg font-semibold tracking-wide text-white"
                        >
                            Indirect
                        </h4>
                        <div class="rounded-full bg-[#AFEC70] p-3">
                            <Waypoints :size="20" class="text-black" />
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-xs text-gray-500">Indirect Invites</p>
                        <h2 class="text-2xl font-semibold text-[#AFEC70]">
                            {{ props.auth.indirect
                            }}<span class="text-xs font-normal">/Networks</span>
                        </h2>
                    </div>
                </div>
                <div
                    class="flex h-32 flex-col justify-between rounded-3xl bg-[#fd8641] p-4"
                >
                    <div class="flex items-center justify-between">
                        <h4
                            class="text-lg font-semibold tracking-wide text-white"
                        >
                            Ranking
                        </h4>
                        <div class="rounded-full bg-[#df6a27] p-3">
                            <SquareStack :size="20" class="text-gray-100" />
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-xs text-gray-100">Package Bonus</p>
                        <h2 class="text-2xl font-semibold text-gray-100">
                            {{ props.auth.indirect
                            }}<span class="text-xs font-normal">/Bonus</span>
                        </h2>
                    </div>
                </div>
                <div
                    class="flex h-32 flex-col justify-between rounded-3xl bg-[#C6EBCD] p-4"
                >
                    <div class="flex items-center justify-between">
                        <h4
                            class="text-lg font-semibold tracking-wide text-[#455b49]"
                        >
                            Cashouts
                        </h4>
                        <div class="rounded-full bg-[#a6d9af] p-3">
                            <Coins :size="20" class="text-black" />
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-xs text-[#455b49]">Reward Cashouts</p>
                        <h2 class="text-2xl font-semibold text-[#455b49]">
                            {{ props.auth.cashouts
                            }}<span class="text-xs font-normal">/History</span>
                        </h2>
                    </div>
                </div>
            </div>

            <button
                class="flex h-12 w-full items-center justify-center space-x-2 rounded-xl border bg-gradient-to-tl from-slate-50 via-slate-100 to-slate-200"
            >
                <h4>View Network</h4>
                <Network :size="18" />
            </button>

            <div class="mt-5 flex flex-col bg-transparent">
                <h6 class="text-gray-500">Network Trends</h6>
                <div
                    class="mt-1 h-full w-full rounded-lg p-3 shadow-sm ring-1 ring-gray-200"
                    id="chart"
                    ref="chart"
                ></div>
            </div>

            <Link
                :href="route('add-member.index')"
                class="mt-4 flex h-28 w-full items-center justify-between rounded-lg bg-gradient-to-tl from-[#458500] to-[#2B542C] p-4 hover:bg-[#3f7b40]"
            >
                <div class="flex flex-col space-y-2">
                    <h2 class="text-lg font-semibold text-[#AFEC70]">
                        Add Member
                    </h2>
                    <p class="text-xs leading-5 text-gray-200">
                        Expand your network by adding members and fostering
                        meaningful connections.
                    </p>
                </div>
                <UserPlus2 :size="92" class="text-[#AFEC70]" />
            </Link>
        </div>
    </AuthenticatedLayout>
</template>
