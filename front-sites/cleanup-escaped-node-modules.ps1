# Nettoyage des "node_modules" échappés à la racine de front-sites.
# À GARDER : uniquement le projet central-app (config + code source).
$root = $PSScriptRoot
$keep = @(
    'package.json',
    'package-lock.json',
    '.package-lock.json',
    'src',
    'public',
    'index.html',
    'vite.config.ts',
    'tsconfig.json',
    'tsconfig.node.json',
    'tailwind.config.js',
    'postcss.config.js',
    '.env.example',
    'README.md',
    '.vite',
    'cleanup-escaped-node-modules.ps1'
)
Get-ChildItem -Path $root -Force | Where-Object { $_.Name -notin $keep } | ForEach-Object {
    Write-Host "Suppression: $($_.Name)"
    Remove-Item -Path $_.FullName -Recurse -Force -ErrorAction SilentlyContinue
}
Write-Host "Nettoyage termine. Lancez: npm install"
