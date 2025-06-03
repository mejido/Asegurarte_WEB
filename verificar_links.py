
import os
import re

# Cambiá esta ruta por donde tengas tus archivos HTML
carpeta = '.'  # <-- EDITAR ESTE CAMPO

# Expresión regular para encontrar links target="_blank" sin rel="noopener"
pattern = re.compile(r'<a\s+[^>]*target="_blank"(?![^>]*rel="noopener")', re.IGNORECASE)

for root, dirs, files in os.walk(carpeta):
    for file in files:
        if file.endswith(".html"):
            path = os.path.join(root, file)
            try:
                with open(path, 'r', encoding='utf-8') as f:
                    contenido = f.read()
                    matches = pattern.findall(contenido)
                    if matches:
                        print(f"⚠️ Archivo: {path}")
                        for m in matches:
                            print(f"  ➔ Link inseguro: {m}")
            except Exception as e:
                print(f"Error leyendo {path}: {e}")
