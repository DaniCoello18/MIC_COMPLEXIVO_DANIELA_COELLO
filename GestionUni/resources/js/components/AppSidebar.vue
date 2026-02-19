<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, GraduationCap, Users, Building, Calendar, FileText, Box } from 'lucide-vue-next';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import AppLogo from './AppLogo.vue';
import { dashboard } from '@/routes';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Alumnos',
        href: '/alumnos',
        icon: GraduationCap,
    },
    {
        title: 'Especialidades',
        href: '/especialidades',
        icon: Users,
    },
    {
        title: 'Profesores',
        href: '/profesores',
        icon: GraduationCap,
    },
    {
        title: 'Edificios',
        href: '/edificios',
        icon: Building,
    },
    {
        title: 'Materias',
        href: '/materias',
        icon: BookOpen,
    },
    {
        title: 'Horarios',
        href: '/horarios',
        icon: Calendar,
    },
    {
        title: 'Matriculas',
        href: '/matriculas',
        icon: FileText,
    },
    {
        title: 'Inventarios',
        href: '/inventarios',
        icon: Box,
    },
    {
        title: 'Inventarios1',
        href: '/inventarios1',
        icon: Box,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
