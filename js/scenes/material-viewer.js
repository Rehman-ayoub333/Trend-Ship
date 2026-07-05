/**
 * Material Viewer Scene - Three.js
 * Plane with noise-based displacement shader
 */

class MaterialViewer {
  constructor(containerId) {
    this.container = document.getElementById(containerId);
    if (!this.container) return;

    this.scene = new THREE.Scene();
    this.camera = new THREE.PerspectiveCamera(45, this.container.offsetWidth / this.container.offsetHeight, 0.1, 1000);
    this.renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    this.renderer.setSize(this.container.offsetWidth, this.container.offsetHeight);
    this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    this.container.appendChild(this.renderer.domElement);

    this.camera.position.z = 5;
    this.initLights();
    this.createMaterial();
    this.addEvents();
    this.animate();
  }

  initLights() {
    const ambient = new THREE.AmbientLight(0xffffff, 0.4);
    this.scene.add(ambient);

    const light = new THREE.DirectionalLight(0xffffff, 1);
    light.position.set(5, 5, 5);
    this.scene.add(light);

    const pointLight = new THREE.PointLight(0xd4857a, 1);
    pointLight.position.set(-5, -5, 5);
    this.scene.add(pointLight);
  }

  createMaterial() {
    this.geometry = new THREE.PlaneGeometry(3, 3, 80, 80);
    
    // Custom shader material mockup (using standard for now with displacement)
    this.material = new THREE.MeshStandardMaterial({
      color: 0xd4c4bc,
      roughness: 0.5,
      metalness: 0.2,
      wireframe: false,
      flatShading: false,
    });

    this.mesh = new THREE.Mesh(this.geometry, this.material);
    this.mesh.rotation.x = -Math.PI / 4;
    this.scene.add(this.mesh);

    // Initial noise displacement simulation
    const pos = this.geometry.attributes.position;
    for (let i = 0; i < pos.count; i++) {
      const x = pos.getX(i);
      const y = pos.getY(i);
      const z = Math.sin(x * 2) * Math.cos(y * 2) * 0.2;
      pos.setZ(i, z);
    }
    pos.needsUpdate = true;
  }

  addEvents() {
    window.addEventListener('resize', () => {
      this.renderer.setSize(this.container.offsetWidth, this.container.offsetHeight);
      this.camera.aspect = this.container.offsetWidth / this.container.offsetHeight;
      this.camera.updateProjectionMatrix();
    });

    // Simple mouse orbit
    this.mouse = { x: 0, y: 0 };
    this.container.addEventListener('mousemove', (e) => {
      const rect = this.container.getBoundingClientRect();
      this.mouse.x = ((e.clientX - rect.left) / this.container.offsetWidth) * 2 - 1;
      this.mouse.y = -((e.clientY - rect.top) / this.container.offsetHeight) * 2 + 1;
    });
  }

  setPreset(type) {
    gsap.to(this.material, {
      duration: 0.8,
      roughness: type === 'silk' ? 0.2 : (type === 'stone' ? 0.8 : 0.5),
      metalness: type === 'silk' ? 0.6 : (type === 'stone' ? 0.1 : 0.2),
      onUpdate: () => {
        // Morph displacement
        const pos = this.geometry.attributes.position;
        const scale = type === 'stone' ? 0.5 : (type === 'silk' ? 0.1 : 0.2);
        for (let i = 0; i < pos.count; i++) {
          const x = pos.getX(i);
          const y = pos.getY(i);
          const z = Math.sin(x * 5) * Math.cos(y * 5) * scale;
          pos.setZ(i, z);
        }
        pos.needsUpdate = true;
      }
    });
  }

  animate() {
    requestAnimationFrame(this.animate.bind(this));
    this.mesh.rotation.y += (this.mouse.x * 0.5 - this.mesh.rotation.y) * 0.05;
    this.mesh.rotation.x += (-this.mouse.y * 0.5 - 0.5 - this.mesh.rotation.x) * 0.05;
    this.renderer.render(this.scene, this.camera);
  }
}

// Global instance if needed
window.MaterialViewer = MaterialViewer;
