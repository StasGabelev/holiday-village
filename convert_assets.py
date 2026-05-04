import os
import subprocess

def convert_assets():
    img_dir = "c:/Users/Stas/.gemini/antigravity/playground/primal-planetoid/holiday-village/images"
    mapping = {
        "IMG_3870.HEIC": "hero.jpg",
        "IMG_3871.HEIC": "lake-swings.jpg",
        "IMG_3868.HEIC": "picnic-1.jpg",
        "IMG_3869.HEIC": "picnic-2.jpg",
        "IMG_3874.HEIC": "playground.jpg",
        "IMG_6157.MOV": "hero-video.mp4"
    }

    for src, dst in mapping.items():
        src_path = os.path.join(img_dir, src)
        dst_path = os.path.join(img_dir, dst)
        
        if os.path.exists(src_path):
            print(f"Converting {src} to {dst}...")
            # Use ffmpeg for both HEIC and MOV
            subprocess.run(["ffmpeg", "-y", "-i", src_path, dst_path], check=True)
            print(f"Successfully created {dst}")

if __name__ == "__main__":
    convert_assets()
