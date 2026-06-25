
function toggleSidebar() { document.getElementById('sidebar').classList.toggle('-translate-x-full'); document.getElementById('overlay').classList.toggle('hidden'); }
        document.getElementById('overlay')?.addEventListener('click', toggleSidebar);

        
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ !== 'undefined') {
                // Inisialisasi Select2 untuk pencarian material
                $('#temp_product').select2({
                    placeholder: "1",
                    width: '100%',
                    dropdownAutoWidth: true
                });

                // Inisialisasi Select2 untuk kategori
                $('#kategoriSelect').select2({
                    placeholder: "1",
                    width: '100%',
                    dropdownAutoWidth: true
                });

                // Event listener Material berubah
                $('#temp_product').on('change', function() {
                    updateTempInfo();
                });
            }
        });

        // ==========================================
        // LOGIKA KATEGORI DINAMIS (AJAX CRUD)
        // ==========================================
        const getSweetAlertConfig = () => {
            const isDark = document.documentElement.classList.contains('dark');
            return {
                background: isDark ? '#1f2937' : '#fff',
                color: isDark ? '#f3f4f6' : '#545454'
            };
        };

        function tambahKategori() {
            const theme = getSweetAlertConfig();
            
            Swal.fire({
                title: '1',
                input: 'text',
                inputPlaceholder: '1',
                showCancelButton: true,
                confirmButtonText: '1',
                cancelButtonText: '1',
                confirmButtonColor: '#D00000',
                showLoaderOnConfirm: true,
                ...theme,
                preConfirm: (kategoriName) => {
                    if (!kategoriName) {
                        Swal.showValidationMessage('1');
                        return false;
                    }
                    
                    return fetch(`1`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '1'
                        },
                        body: JSON.stringify({ nama_kategori: kategoriName })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(errInfo => {
                                throw new Error(errInfo.error || '1');
                            });
                        }
                        return response.json();
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`1 ${error.message}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    const newKategori = result.value.nama;
                    const newId = result.value.id;
                    
                    const select = $('#kategoriSelect');
                    const newOption = new Option(newKategori, newKategori, true, true);
                    $(newOption).attr('data-id', newId);
                    select.append(newOption).trigger('change');
                    
                    Swal.fire({icon: 'success', title: '1', text: '1', timer: 1500, showConfirmButton: false, ...theme});
                }
            });
        }

        function editKategori() {
            const select = $('#kategoriSelect');
            const selectedOption = select.find('option:selected');
            const id = selectedOption.attr('data-id');
            const currentName = selectedOption.val();
            const theme = getSweetAlertConfig();

            if (!id || !currentName) {
                Swal.fire({icon: 'warning', title: 'Oops...', text: 'Pilih kategori yang ingin diubah terlebih dahulu!', confirmButtonColor: '#D00000', ...theme});
                return;
            }

            Swal.fire({
                title: 'Edit Kategori',
                input: 'text',
                inputValue: currentName,
                inputPlaceholder: 'Nama Kategori Baru',
                showCancelButton: true,
                confirmButtonText: 'Simpan Perubahan',
                cancelButtonText: '1',
                confirmButtonColor: '#D00000',
                showLoaderOnConfirm: true,
                ...theme,
                preConfirm: (newName) => {
                    if (!newName || newName === currentName) {
                        Swal.showValidationMessage('Nama kategori tidak boleh kosong atau sama.');
                        return false;
                    }
                    
                    return fetch(`1/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '1'
                        },
                        body: JSON.stringify({ nama_kategori: newName })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(errInfo => {
                                throw new Error(errInfo.error || 'Gagal mengubah kategori.');
                            });
                        }
                        return response.json();
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`1 ${error.message}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    const newName = result.value.nama;
                    selectedOption.val(newName).text(newName);
                    select.trigger('change');
                    Swal.fire({icon: 'success', title: 'Berhasil', text: 'Kategori berhasil diubah', timer: 1500, showConfirmButton: false, ...theme});
                }
            });
        }

        function hapusKategori() {
            const select = $('#kategoriSelect');
            const selectedOption = select.find('option:selected');
            const id = selectedOption.attr('data-id');
            const currentName = selectedOption.val();
            const theme = getSweetAlertConfig();

            if (!id || !currentName) {
                Swal.fire({icon: 'warning', title: 'Oops...', text: 'Pilih kategori yang ingin dihapus terlebih dahulu!', confirmButtonColor: '#D00000', ...theme});
                return;
            }

            Swal.fire({
                title: 'Hapus Kategori?',
                text: `Anda yakin ingin menghapus kategori "${currentName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D00000',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                ...theme
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`1/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '1'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal menghapus kategori.');
                        return response.json();
                    })
                    .then(data => {
                        selectedOption.remove();
                        select.val('').trigger('change');
                        Swal.fire({icon: 'success', title: 'Terhapus!', text: 'Kategori berhasil dihapus.', timer: 1500, showConfirmButton: false, ...theme});
                    })
                    .catch(error => {
                        Swal.fire({icon: 'error', title: 'Gagal', text: error.message, confirmButtonColor: '#D00000', ...theme});
                    });
                }
            });
        }

        // ==========================================
        // ==========================================
        // LOGIKA KERANJANG BARANG KELUAR (POS)
        // ==========================================
        let cartItems = {};

        function addToCartQuick(id, name, price, stock, satuan) {
            const isDark = document.documentElement.classList.contains('dark');
            const bgPopup = isDark ? '#1f2937' : '#fff';
            const colorText = isDark ? '#f3f4f6' : '#545454';

            if (stock <= 0) {
                Swal.fire({ icon: 'warning', title: 'Stok Habis', text: 'Stok barang ini kosong.', confirmButtonColor: '#D00000', background: bgPopup, color: colorText });
                return;
            }

            if (cartItems[id]) {
                if (cartItems[id].qty >= stock) {
                    Swal.fire({ icon: 'error', title: 'Stok Tidak Cukup', text: `Maksimal stok yang tersedia hanya ${stock} ${satuan}.`, confirmButtonColor: '#D00000', background: bgPopup, color: colorText });
                    return;
                }
                cartItems[id].qty++;
            } else {
                cartItems[id] = { id, name, price: parseInt(price), qty: 1, stock: parseInt(stock), satuan };
            }
            renderCart();
            
            // Animasi Feedback
            const card = document.querySelector(`.product-card[data-category][onclick*="'${id}'"]`);
            if(card) {
                card.style.transform = 'scale(0.95)';
                setTimeout(() => card.style.transform = '', 150);
            }
        }

        function updateCartQty(id, delta) {
            const isDark = document.documentElement.classList.contains('dark');
            const bgPopup = isDark ? '#1f2937' : '#fff';
            const colorText = isDark ? '#f3f4f6' : '#545454';

            if (cartItems[id]) {
                const newQty = cartItems[id].qty + delta;
                if (newQty <= 0) {
                    delete cartItems[id];
                } else if (newQty > cartItems[id].stock) {
                    Swal.fire({ icon: 'error', title: 'Stok Tidak Cukup', text: `Maksimal stok yang tersedia hanya ${cartItems[id].stock} ${cartItems[id].satuan}.`, confirmButtonColor: '#D00000', background: bgPopup, color: colorText });
                    return;
                } else {
                    cartItems[id].qty = newQty;
                }
                renderCart();
            }
        }

        
        function updateCartQtyDirect(id, newQty) {
            const isDark = document.documentElement.classList.contains('dark');
            const bgPopup = isDark ? '#1f2937' : '#fff';
            const colorText = isDark ? '#f3f4f6' : '#545454';
            
            if (cartItems[id]) {
                const qty = parseInt(newQty);
                if (isNaN(qty) || qty <= 0) {
                    delete cartItems[id];
                } else if (qty > cartItems[id].stock) {
                    Swal.fire({ icon: 'error', title: 'Stok Tidak Cukup', text: `Maksimal stok yang tersedia hanya ${cartItems[id].stock} ${cartItems[id].satuan}.`, confirmButtonColor: '#D00000', background: bgPopup, color: colorText });
                    cartItems[id].qty = cartItems[id].stock;
                } else {
                    cartItems[id].qty = qty;
                }
                renderCart();
            }
        }

        function handleScannerInput(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const searchVal = e.target.value.toLowerCase().trim();
                if (!searchVal) return;

                const cards = document.querySelectorAll('.product-card');
                let exactMatch = null;
                let matchCount = 0;

                cards.forEach(card => {
                    const sku = card.getAttribute('data-sku') || '';
                    const barcode = card.getAttribute('data-barcode') || '';
                    if (!card.classList.contains('hidden')) {
                        matchCount++;
                    }
                    if (sku === searchVal || barcode.toLowerCase() === searchVal) {
                        exactMatch = card;
                    }
                });

                if (exactMatch) {
                    exactMatch.click();
                    e.target.value = '';
                    filterSearch();
                } else if (matchCount === 1) {
                    const visibleCard = document.querySelector('.product-card:not(.hidden)');
                    if (visibleCard) {
                        visibleCard.click();
                        e.target.value = '';
                        filterSearch();
                    }
                }
            } else {
                filterSearch();
            }
        }

        function updateCartPrice(id, newPrice) {
            if (cartItems[id]) {
                cartItems[id].price = parseInt(newPrice.replace(/[^0-9]/g, '')) || 0;
                renderCart();
            }
        }

        function removeCartItem(id) {
            delete cartItems[id];
            renderCart();
        }

        function clearCart() {
            cartItems = {};
            renderCart();
        }

        function calculateTotal() {
            let subtotalAll = 0;
            for (const id in cartItems) {
                subtotalAll += (cartItems[id].qty * cartItems[id].price);
            }
            
            const diskonInput = document.getElementById('inputDiskon');
            let diskon = 0;
            if (diskonInput) {
                diskon = parseInt(diskonInput.value.replace(/[^0-9]/g, '')) || 0;
            }
            
            let finalTotal = subtotalAll - diskon;
            if(finalTotal < 0) finalTotal = 0;
            
            document.getElementById('grandTotal').innerText = 'Rp ' + finalTotal.toLocaleString('id-ID');
        }

        function renderCart() {
            const list = document.getElementById('cartList');
            const emptyMsg = document.getElementById('emptyCartMsg');
            let total = 0;
            let count = 0;
            
            list.innerHTML = '';
            
            if (Object.keys(cartItems).length === 0) {
                emptyMsg.classList.remove('hidden');
                document.getElementById('totalItems').innerText = 0;
                document.getElementById('grandTotal').innerText = 'Rp 0';
                return;
            }
            
            emptyMsg.classList.add('hidden');
            
            for (const id in cartItems) {
                const item = cartItems[id];
                const subtotal = item.qty * item.price;
                total += subtotal;
                count++;
                
                const li = document.createElement('li');
                li.className = 'group bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-[0_4px_15px_-5px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_25px_-5px_rgba(0,0,0,0.1)] hover:border-red-100 dark:hover:border-red-900/50 transition-all duration-300 relative overflow-hidden flex flex-col gap-3';
                li.innerHTML = `
                    <div class="flex flex-col px-2 py-1.5 w-full relative group">
                        <div class="flex justify-between items-start mb-1.5">
                            <h4 class="font-bold text-sm text-gray-800 dark:text-gray-100 leading-tight line-clamp-1 flex-1 pr-2 cursor-default" title="${item.name}">${item.name}</h4>
                            <button type="button" onclick="removeCartItem('${id}')" class="text-gray-300 dark:text-gray-600 hover:text-red-500 dark:hover:text-red-400 transition-colors p-0.5"><i class="fas fa-times text-xs"></i></button>
                        </div>
                        <div class="flex items-center justify-between gap-1.5 bg-white dark:bg-gray-800 rounded-lg p-1.5 border border-gray-200 dark:border-gray-700 shadow-sm group-hover:border-red-200 transition-colors">
                            <div class="flex items-center bg-gray-50 dark:bg-gray-900 px-1.5 rounded border border-gray-200 dark:border-gray-700">
                                <span class="text-[9px] text-gray-400 mr-1">Rp</span>
                                <input type="text" value="${item.price.toLocaleString('id-ID')}" onchange="updateCartPrice('${id}', this.value)" onkeyup="this.value=this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')" class="w-16 text-xs font-bold p-0 border-none bg-transparent text-gray-800 dark:text-white focus:ring-0 h-6">
                            </div>
                            <span class="text-gray-300 text-[9px]"><i class="fas fa-times"></i></span>
                            <div class="flex items-center bg-gray-50 dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-700">
                                <button type="button" onclick="updateCartQty('${id}', -1)" class="w-6 h-6 flex items-center justify-center text-gray-500 hover:bg-red-100 hover:text-red-600 transition-colors rounded-l border-r border-gray-200 dark:border-gray-700"><i class="fas fa-minus text-[8px]"></i></button>
                                <input type="number" value="${item.qty}" onchange="updateCartQtyDirect('${id}', this.value)" class="w-8 text-center text-xs font-black p-0 border-none bg-transparent text-gray-800 dark:text-white focus:ring-0 h-6 appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                <button type="button" onclick="updateCartQty('${id}', 1)" class="w-6 h-6 flex items-center justify-center text-gray-500 hover:bg-green-100 hover:text-green-600 transition-colors rounded-r border-l border-gray-200 dark:border-gray-700"><i class="fas fa-plus text-[8px]"></i></button>
                            </div>
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-200 uppercase ml-1 min-w-[28px]">${item.satuan}</span>
                            <div class="flex-1 text-right ml-1">
                                <p class="font-black text-[#D00000] dark:text-red-400 text-[13px] tracking-tight leading-none">Rp ${subtotal.toLocaleString('id-ID')}</p>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="product_id[]" value="${id}">
                    <input type="hidden" name="qty[]" value="${item.qty}">
                    <input type="hidden" name="price[]" value="${item.price}">
                `;
                list.appendChild(li);
            }
            
            document.getElementById('totalItems').innerText = count;
            calculateTotal();
        }

        function filterCategory(catId) {
              document.querySelectorAll('.category-btn').forEach(btn => {
                  btn.className = "category-btn px-5 py-2 rounded-full text-xs font-bold bg-transparent border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 whitespace-nowrap transition-all";
              });
              const activeBtn = document.querySelector(`.category-btn[data-category="${catId}"]`);
              if(activeBtn) {
                  activeBtn.className = "category-btn active px-5 py-2 rounded-full text-xs font-bold bg-slate-800 dark:bg-slate-700 text-white shadow-md border border-slate-700 whitespace-nowrap transition-all";
              }
          }

          function filterSearch() {
            const activeCat = document.querySelector('.category-btn.active')?.getAttribute('data-category') || 'all';
            filterCategory(activeCat);
        }

        function confirmSubmitPOS(e) {
              if(e) e.preventDefault();
              const isDark = document.documentElement.classList.contains('dark');
              const theme = {
                  background: isDark ? '#1f2937' : '#fff',
                  color: isDark ? '#f3f4f6' : '#545454'
              };
  
              if (Object.keys(cartItems).length === 0) {
                  Swal.fire({ icon: 'warning', title: 'Keranjang Kosong', text: 'Pilih minimal 1 barang.', confirmButtonColor: '#D00000', ...theme });
                  return false;
              }
              
              // Open Checkout Modal
              document.getElementById('modalGrandTotal').innerText = document.getElementById('grandTotal').innerText;
              document.getElementById('modalItemCount').innerText = document.getElementById('totalItems').innerText + " Item";
              
              const modal = document.getElementById('checkoutModal');
              const modalContent = document.getElementById('checkoutModalContent');
              modal.classList.remove('hidden');
              setTimeout(() => {
                  modalContent.classList.remove('scale-95', 'opacity-0');
                  modalContent.classList.add('scale-100', 'opacity-100');
              }, 10);
          }

          function closeCheckoutModal() {
              const modal = document.getElementById('checkoutModal');
              const modalContent = document.getElementById('checkoutModalContent');
              modalContent.classList.remove('scale-100', 'opacity-100');
              modalContent.classList.add('scale-95', 'opacity-0');
              setTimeout(() => {
                  modal.classList.add('hidden');
              }, 300);
          }

          function submitFinalPOS() {
              const katSelect = document.getElementById('kategoriSelect');
              const tujuanInput = document.querySelector('input[name="tujuan"]');
              
              const isDark = document.documentElement.classList.contains('dark');
              const theme = { background: isDark ? '#1f2937' : '#fff', color: isDark ? '#f3f4f6' : '#545454' };

              if(!katSelect.value) {
                  Swal.fire({ icon: 'warning', title: 'Kategori Kosong', text: 'Silakan pilih kategori keluar.', confirmButtonColor: '#D00000', ...theme });
                  katSelect.focus();
                  return false;
              }
              if(!tujuanInput.value.trim()) {
                  Swal.fire({ icon: 'warning', title: 'Tujuan Kosong', text: 'Silakan isi tujuan pengeluaran barang.', confirmButtonColor: '#D00000', ...theme });
                  tujuanInput.focus();
                  return false;
              }

              // Submit the form
              Swal.fire({
                  title: 'Menyimpan Transaksi...',
                  text: 'Mohon tunggu sebentar',
                  allowOutsideClick: false,
                  showConfirmButton: false,
                  ...theme,
                  didOpen: () => {
                      Swal.showLoading();
                      document.getElementById('posForm').submit();
                  }
              });
          }
        });
        
    