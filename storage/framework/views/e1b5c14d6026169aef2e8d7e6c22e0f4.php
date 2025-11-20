

<?php $__env->startSection('title', 'Reporting & KPIs - GIDA'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Header -->
    <div class="admin-header">
        <h1>Reporting & KPIs</h1>
        <div>
            <button class="admin-btn" onclick="window.location.href='<?php echo e(route('dashboard')); ?>'">← Retour au dashboard</button>
        </div>
    </div>

    <!-- Statistiques globales -->
    <div class="stats-grid" style="grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
        <div class="stat-card" style="padding: 16px; min-height: auto;">
            <h3 style="font-size: 14px; margin-bottom: 8px;">Total demandes</h3>
            <div class="number" style="font-size: 24px; margin-bottom: 4px;"><?php echo e($stats['total']); ?></div>
            <div class="trend" style="font-size: 12px;">Toutes périodes</div>
        </div>
        <div class="stat-card" style="padding: 16px; min-height: auto;">
            <h3 style="font-size: 14px; margin-bottom: 8px;">Taux de résolution</h3>
            <div class="number" style="font-size: 24px; margin-bottom: 4px;"><?php echo e($stats['total'] > 0 ? round(($stats['cloturees'] / $stats['total']) * 100, 1) : 0); ?>%</div>
            <div class="trend" style="color: #28a745; font-size: 12px;">Performance</div>
        </div>
        <div class="stat-card" style="padding: 16px; min-height: auto;">
            <h3 style="font-size: 14px; margin-bottom: 8px;">Temps moyen</h3>
            <div class="number" style="font-size: 24px; margin-bottom: 4px;">2.5j</div>
            <div class="trend" style="color: #28a745; font-size: 12px;">-0.5j ce mois</div>
        </div>
        <div class="stat-card" style="padding: 16px; min-height: auto;">
            <h3 style="font-size: 14px; margin-bottom: 8px;">Satisfaction</h3>
            <div class="number" style="font-size: 24px; margin-bottom: 4px;">4.2/5</div>
            <div class="trend" style="color: #28a745; font-size: 12px;">+0.3 ce mois</div>
        </div>
    </div>

    <!-- Graphiques -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px;">
        <!-- Graphique par statut -->
        <div style="background: white; border-radius: 4px; padding: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
            <h3 style="margin-bottom: 6px; color: #1a1a2e; font-size: 12px;">Demandes par statut</h3>
            <canvas id="statusChart" width="180" height="90"></canvas>
        </div>
        
        <!-- Graphique par catégorie -->
        <div style="background: white; border-radius: 4px; padding: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
            <h3 style="margin-bottom: 6px; color: #1a1a2e; font-size: 12px;">Demandes par catégorie</h3>
            <canvas id="categoryChart" width="180" height="90"></canvas>
        </div>
    </div>

    <!-- Graphique par société -->
    <div style="background: white; border-radius: 4px; padding: 16px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); margin-bottom: 16px;">
        <h3 style="margin-bottom: 12px; color: #1a1a2e; font-size: 14px;">Répartition des demandes par société</h3>
        <canvas id="societeChart" height="100"></canvas>
    </div>

  
        
            

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Graphique par statut
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['En attente', 'En cours', 'À risque', 'Clôturé'],
                datasets: [{
                    data: [
                        <?php echo e($stats['total'] - $stats['en_cours'] - $stats['a_risque'] - $stats['cloturees']); ?>,
                        <?php echo e($stats['en_cours']); ?>,
                        <?php echo e($stats['a_risque']); ?>,
                        <?php echo e($stats['cloturees']); ?>

                    ],
                    backgroundColor: [
                        '#ffc107',
                        '#17a2b8',
                        '#dc3545',
                        '#28a745'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Graphique par catégorie
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($stats['par_categorie']->keys()); ?>,
                datasets: [{
                    label: 'Nombre de demandes',
                    data: <?php echo json_encode($stats['par_categorie']->values()); ?>,
                    backgroundColor: '#0f3460',
                    borderColor: '#16213e',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Graphique par société
        const societeCtx = document.getElementById('societeChart').getContext('2d');
        const societeData = <?php echo json_encode($stats['par_societe']->toArray()); ?>;
        const societeLabels = Object.keys(societeData);
        const societeValues = Object.values(societeData);
        
        // Couleurs pour chaque société
        const backgroundColors = [
            'rgba(15, 52, 96, 0.7)',   // Bleu foncé
            'rgba(220, 53, 69, 0.7)',  // Rouge
            'rgba(40, 167, 69, 0.7)',  // Vert
            'rgba(255, 193, 7, 0.7)',  // Jaune
            'rgba(23, 162, 184, 0.7)', // Cyan
            'rgba(108, 117, 125, 0.7)' // Gris
        ];
        
        new Chart(societeCtx, {
            type: 'bar',
            data: {
                labels: societeLabels,
                datasets: [{
                    label: 'Nombre de demandes',
                    data: societeValues,
                    backgroundColor: backgroundColors.slice(0, societeLabels.length),
                    borderColor: '#16213e',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nombre de demandes'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Sociétés'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false
                    }
                }
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appAdministration', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Gida\resources\views/admin/reporting.blade.php ENDPATH**/ ?>