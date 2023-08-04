<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-end">
                    <PrimaryButton class="m-5">
                        <Link :href="route('file.exporter.create')"
                            >Create Format</Link
                        >
                    </PrimaryButton>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="relative overflow-x-auto">
                        <table
                            class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                        >
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                            >
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Format name
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-right"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item, index_item) in propExporters"
                                    :key="index_item"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                                >
                                    <td class="px-6 py-4">{{ item?.name }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end">
                                            <PrimaryButton
                                                class="m-5 bg-yellow-200 dark:bg-yellow-200"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'file.exporter.edit',
                                                            {
                                                                id: item?.id,
                                                            }
                                                        )
                                                    "
                                                    >Edit</Link
                                                >
                                            </PrimaryButton>
                                            <PrimaryButton
                                                class="m-5 bg-red-300 dark:bg-red-300"
                                                @click="deletePrompt(item?.id)"
                                            >
                                                Delete
                                            </PrimaryButton>
                                            <PrimaryButton
                                                class="m-5"
                                                @click="exportFile(item?.id)"
                                            >
                                                Export
                                            </PrimaryButton>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import Swal from "sweetalert2";

export default {
    props: ["propExporters"],

    components: {
        PrimaryButton,
        AuthenticatedLayout,
        Head,
        Link,
    },

    data() {
        return {};
    },

    methods: {
        exportFile(id) {
            window.location = route("file.exporter.export", { id });
        },

        async deletePrompt(id) {
            const result = await Swal.fire({
                text: "Are you sure ?",
                showConfirmButton: true,
                confirmButtonText: "Yes",
                showCancelButton: true,
                showLoaderOnConfirm: true,
            });

            if (result?.isConfirmed) this.destroy(id);
        },

        async destroy(id) {
            location = route("file.exporter.destroy", { id });
        },
    },
};
</script>
